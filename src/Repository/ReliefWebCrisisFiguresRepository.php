<?php

namespace App\Repository;

use App\Entity\ReliefWebCrisisFigures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReliefWebCrisisFigures>
 *
 * @method ReliefWebCrisisFigures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReliefWebCrisisFigures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReliefWebCrisisFigures[]    findAll()
 * @method ReliefWebCrisisFigures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReliefWebCrisisFiguresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReliefWebCrisisFigures::class);
    }

    public function save(ReliefWebCrisisFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReliefWebCrisisFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReliefWebCrisisFigures[] Returns an array of ReliefWebCrisisFigures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReliefWebCrisisFigures
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
