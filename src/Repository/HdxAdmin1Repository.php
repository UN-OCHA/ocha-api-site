<?php

namespace App\Repository;

use App\Entity\HdxAdmin1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HdxAdmin1>
 *
 * @method HdxAdmin1|null find($id, $lockMode = null, $lockVersion = null)
 * @method HdxAdmin1|null findOneBy(array $criteria, array $orderBy = null)
 * @method HdxAdmin1[]    findAll()
 * @method HdxAdmin1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HdxAdmin1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HdxAdmin1::class);
    }

    public function save(HdxAdmin1 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HdxAdmin1 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HdxAdmin1[] Returns an array of HdxAdmin1 objects
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

//    public function findOneBySomeField($value): ?HdxAdmin1
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
