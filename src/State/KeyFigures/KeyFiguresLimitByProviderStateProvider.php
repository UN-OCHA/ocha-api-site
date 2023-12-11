<?php

declare(strict_types=1);

namespace App\State\KeyFigures;

use ApiPlatform\Doctrine\Common\State\LinksHandlerTrait as CommonLinksHandlerTrait;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Exception\RuntimeException;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;
use App\State\KeyFigures\KeyFigureProviderTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class KeyFiguresLimitByProviderStateProvider implements ProviderInterface
{
    use KeyFigureProviderTrait;
    use CommonLinksHandlerTrait;

    /**
     * @param QueryCollectionExtensionInterface[] $collectionExtensions
     */
    public function __construct(
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly ManagerRegistry $managerRegistry,
        private TokenStorageInterface $tokenStorage,
        private readonly iterable $collectionExtensions = [],
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();
        /** @var EntityManagerInterface $manager */
        $manager = $this->managerRegistry->getManagerForClass($resourceClass);

        /** @var \Doctrine\ORM\EntityRepository $repository */
        $repository = $manager->getRepository($resourceClass);
        if (!method_exists($repository, 'createQueryBuilder')) {
            throw new RuntimeException('The repository class must have a "createQueryBuilder" method.');
        }

        $queryBuilder = $repository->createQueryBuilder('o');
        $queryNameGenerator = new QueryNameGenerator();

        $this->handleLinks($queryBuilder, $uriVariables, $queryNameGenerator, $context, $resourceClass, $operation);

        $this->checkProviderAccess($operation, $context);

        // Limit to provider specified on endpoint.
        $provider = $this->getProvider($operation, $context);
        if ($provider) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

        // Check user roles if not admin.
        /** @var \App\Entity\User */
        if ($this->tokenStorage->getToken() && $user = $this->tokenStorage->getToken()->getUser()) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                if (!empty($user->getCanRead())) {
                    $queryBuilder->andWhere($queryBuilder->expr()->in('o.provider', ':providers'))
                        ->setParameter(':providers', $user->getCanRead());
                }
                else {
                    throw new BadRequestException('User needs to have at least one can_read entry.');
                }
            }
        }

        // Collection.
        if ($operation instanceof CollectionOperationInterface) {
            foreach ($this->collectionExtensions as $extension) {
                $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operation, $context);

                if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operation, $context)) {
                    return $extension->getResult($queryBuilder, $resourceClass, $operation, $context);
                }
            }

            return $queryBuilder->getQuery()->getResult();
        }

        // Simple get.
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    private function handleLinks(QueryBuilder $queryBuilder, array $identifiers, QueryNameGenerator $queryNameGenerator, array $context, string $entityClass, Operation $operation): void
    {
        if (!$identifiers) {
            return;
        }

        $manager = $this->managerRegistry->getManagerForClass($entityClass);
        $doctrineClassMetadata = $manager->getClassMetadata($entityClass);
        $alias = $queryBuilder->getRootAliases()[0];

        $links = $this->getLinks($entityClass, $operation, $context);

        if (!$links) {
            return;
        }

        $previousAlias = $alias;
        $previousJoinProperties = $doctrineClassMetadata->getIdentifierFieldNames();
        $expressions = [];
        $identifiers = array_reverse($identifiers);

        foreach (array_reverse($links) as $link) {
            if (null !== $link->getExpandedValue() || !$link->getFromClass()) {
                continue;
            }

            $fromClass = $link->getFromClass();
            if (!$this->managerRegistry->getManagerForClass($fromClass)) {
                $fromClass = $this->getLinkFromClass($link, $operation);
            }

            $fromClassMetadata = $manager->getClassMetadata($fromClass);
            $identifierProperties = $link->getIdentifiers();
            $hasCompositeIdentifiers = 1 < \count($identifierProperties);

            if (!$link->getFromProperty() && !$link->getToProperty()) {
                $currentAlias = $fromClass === $entityClass ? $alias : $queryNameGenerator->generateJoinAlias($alias);

                foreach ($identifierProperties as $identifierProperty) {
                    $placeholder = $queryNameGenerator->generateParameterName($identifierProperty);
                    $queryBuilder->andWhere("$currentAlias.$identifierProperty = :$placeholder");
                    $queryBuilder->setParameter($placeholder, $this->getIdentifierValue($identifiers, $hasCompositeIdentifiers ? $identifierProperty : null), $fromClassMetadata->getTypeOfField($identifierProperty));
                }

                $previousAlias = $currentAlias;
                $previousJoinProperties = $fromClassMetadata->getIdentifierFieldNames();
                continue;
            }

            $joinProperties = $doctrineClassMetadata->getIdentifierFieldNames();

            if ($link->getFromProperty() && !$link->getToProperty()) {
                $joinAlias = $queryNameGenerator->generateJoinAlias('m');
                $associationMapping = $fromClassMetadata->getAssociationMapping($link->getFromProperty()); // @phpstan-ignore-line
                $relationType = $associationMapping['type'];

                if ($relationType & ClassMetadataInfo::TO_MANY) {
                    $nextAlias = $queryNameGenerator->generateJoinAlias($alias);
                    $whereClause = [];
                    foreach ($identifierProperties as $identifierProperty) {
                        $placeholder = $queryNameGenerator->generateParameterName($identifierProperty);
                        $whereClause[] = "$nextAlias.{$identifierProperty} = :$placeholder";
                        $queryBuilder->setParameter($placeholder, $this->getIdentifierValue($identifiers, $hasCompositeIdentifiers ? $identifierProperty : null), $fromClassMetadata->getTypeOfField($identifierProperty));
                    }

                    $property = $associationMapping['mappedBy'] ?? $joinProperties[0];
                    $select = isset($associationMapping['mappedBy']) ? "IDENTITY($joinAlias.$property)" : "$joinAlias.$property";
                    $expressions["$previousAlias.{$property}"] = "SELECT $select FROM {$fromClass} $nextAlias INNER JOIN $nextAlias.{$associationMapping['fieldName']} $joinAlias WHERE ".implode(' AND ', $whereClause);
                    $previousAlias = $nextAlias;
                    continue;
                }

                // A single-valued association path expression to an inverse side is not supported in DQL queries.
                if ($relationType & ClassMetadataInfo::TO_ONE && !($associationMapping['isOwningSide'] ?? true)) {
                    $queryBuilder->innerJoin("$previousAlias.".$associationMapping['mappedBy'], $joinAlias);
                } else {
                    $queryBuilder->join(
                        $fromClass,
                        $joinAlias,
                        'WITH',
                        "$previousAlias.{$previousJoinProperties[0]} = $joinAlias.{$associationMapping['fieldName']}"
                    );
                }

                foreach ($identifierProperties as $identifierProperty) {
                    $placeholder = $queryNameGenerator->generateParameterName($identifierProperty);
                    $queryBuilder->andWhere("$joinAlias.$identifierProperty = :$placeholder");
                    $queryBuilder->setParameter($placeholder, $this->getIdentifierValue($identifiers, $hasCompositeIdentifiers ? $identifierProperty : null), $fromClassMetadata->getTypeOfField($identifierProperty));
                }

                $previousAlias = $joinAlias;
                $previousJoinProperties = $joinProperties;
                continue;
            }

            $joinAlias = $queryNameGenerator->generateJoinAlias($alias);
            $queryBuilder->join("{$previousAlias}.{$link->getToProperty()}", $joinAlias);

            foreach ($identifierProperties as $identifierProperty) {
                $placeholder = $queryNameGenerator->generateParameterName($identifierProperty);
                $queryBuilder->andWhere("$joinAlias.$identifierProperty = :$placeholder");
                $queryBuilder->setParameter($placeholder, $this->getIdentifierValue($identifiers, $hasCompositeIdentifiers ? $identifierProperty : null), $fromClassMetadata->getTypeOfField($identifierProperty));
            }

            $previousAlias = $joinAlias;
            $previousJoinProperties = $joinProperties;
        }

        if ($expressions) {
            $i = 0;
            $clause = '';
            foreach ($expressions as $alias => $expression) {
                if (0 === $i) {
                    $clause .= "$alias IN (".$expression;
                    ++$i;
                    continue;
                }

                $clause .= " AND $alias IN (".$expression;
                ++$i;
            }

            $queryBuilder->andWhere($clause.str_repeat(')', $i));
        }
    }

    private function getLinkFromClass(Link $link, Operation $operation): string
    {
        $fromClass = $link->getFromClass();
        if ($fromClass === $operation->getClass() && $entityClass = $this->getStateOptionsEntityClass($operation)) {
            return $entityClass;
        }

        $operation = $this->resourceMetadataCollectionFactory->create($fromClass)->getOperation();

        if ($entityClass = $this->getStateOptionsEntityClass($operation)) {
            return $entityClass;
        }

        throw new \Exception('Can not found a doctrine class for this link.');
    }

    private function getStateOptionsEntityClass(Operation $operation): ?string
    {
        if (($options = $operation->getStateOptions()) && $options instanceof Options && $entityClass = $options->getEntityClass()) {
            return $entityClass;
        }

        return null;
    }
}
