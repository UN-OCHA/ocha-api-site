<?php

namespace App\Repository;

use App\Entity\ReliefWebCrisisFigureValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReliefWebCrisisFigureValue>
 *
 * @method ReliefWebCrisisFigureValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReliefWebCrisisFigureValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReliefWebCrisisFigureValue[]    findAll()
 * @method ReliefWebCrisisFigureValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReliefWebCrisisFigureValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReliefWebCrisisFigureValue::class);
    }

    public function save(ReliefWebCrisisFigureValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReliefWebCrisisFigureValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReliefWebCrisisFigureValue[] Returns an array of ReliefWebCrisisFigureValue objects
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

//    public function findOneBySomeField($value): ?ReliefWebCrisisFigureValue
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
