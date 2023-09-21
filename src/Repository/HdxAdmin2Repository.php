<?php

namespace App\Repository;

use App\Entity\HdxAdmin2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HdxAdmin2>
 *
 * @method HdxAdmin2|null find($id, $lockMode = null, $lockVersion = null)
 * @method HdxAdmin2|null findOneBy(array $criteria, array $orderBy = null)
 * @method HdxAdmin2[]    findAll()
 * @method HdxAdmin2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HdxAdmin2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HdxAdmin2::class);
    }

    public function save(HdxAdmin2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HdxAdmin2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HdxAdmin2[] Returns an array of HdxAdmin2 objects
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

//    public function findOneBySomeField($value): ?HdxAdmin2
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
