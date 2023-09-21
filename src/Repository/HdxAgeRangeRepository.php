<?php

namespace App\Repository;

use App\Entity\HdxAgeRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HdxAgeRange>
 *
 * @method HdxAgeRange|null find($id, $lockMode = null, $lockVersion = null)
 * @method HdxAgeRange|null findOneBy(array $criteria, array $orderBy = null)
 * @method HdxAgeRange[]    findAll()
 * @method HdxAgeRange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HdxAgeRangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HdxAgeRange::class);
    }

    public function save(HdxAgeRange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HdxAgeRange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HdxAgeRange[] Returns an array of HdxAgeRange objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HdxAgeRange
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
