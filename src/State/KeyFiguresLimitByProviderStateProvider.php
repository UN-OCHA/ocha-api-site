<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\LinksHandlerTrait;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Exception\RuntimeException;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;
use App\State\KeyFigureProviderTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Collection state provider using the Doctrine ORM.
 *
 * @author Kévin Dunglas <kevin@dunglas.fr>
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
final class KeyFiguresLimitByProviderStateProvider implements ProviderInterface
{
    use KeyFigureProviderTrait;
    use LinksHandlerTrait;

    /**
     * @param QueryCollectionExtensionInterface[] $collectionExtensions
     */
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
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
}
