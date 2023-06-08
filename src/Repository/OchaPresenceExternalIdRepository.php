<?php

namespace App\Repository;

use App\Entity\OchaPresenceExternalId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OchaPresenceExternalId>
 *
 * @method OchaPresenceExternalId|null find($id, $lockMode = null, $lockVersion = null)
 * @method OchaPresenceExternalId|null findOneBy(array $criteria, array $orderBy = null)
 * @method OchaPresenceExternalId[]    findAll()
 * @method OchaPresenceExternalId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OchaPresenceExternalIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OchaPresenceExternalId::class);
    }

    public function save(OchaPresenceExternalId $entity, bool $flush = false): void
    {
        trigger_deprecation(__CLASS__, __FUNCTION__, 'called');
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OchaPresenceExternalId $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OchaPresenceExternalId[] Returns an array of OchaPresenceExternalId objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OchaPresenceExternalId
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
