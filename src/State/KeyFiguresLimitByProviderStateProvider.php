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
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * Collection state provider using the Doctrine ORM.
 *
 * @author Kévin Dunglas <kevin@dunglas.fr>
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
final class KeyFiguresLimitByProviderStateProvider implements ProviderInterface
{
    use LinksHandlerTrait;

    /**
     * @param QueryCollectionExtensionInterface[] $collectionExtensions
     */
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly ManagerRegistry $managerRegistry,
        private Security $security,
        private readonly iterable $collectionExtensions = [],
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
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

        // Limit to provider.
        /** @var \ApiPlatform\Metadata\GetCollection $operation */
        $operation = $context['operation'];
        $properties = $operation->getExtraProperties() ?? [];
        $provider = $properties['provider'] ?? NULL;

        if ($provider) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

//        // Check user roles.
//        /** @var \App\Entity\User */
//        if ($user = $this->security->getUser()) {
//            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
//                $queryBuilder->andWhere($queryBuilder->expr()->eq('o.provider', ':provider'))
//                    ->setParameter(':provider', $user->getUsername());
//            }
//        }

        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operation, $context);

            if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operation, $context)) {
                return $extension->getResult($queryBuilder, $resourceClass, $operation, $context);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
