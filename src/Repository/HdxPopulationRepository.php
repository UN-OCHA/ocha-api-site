<?php

namespace App\Repository;

use App\Entity\HdxPopulation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HdxPopulation>
 *
 * @method HdxPopulation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HdxPopulation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HdxPopulation[]    findAll()
 * @method HdxPopulation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HdxPopulationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HdxPopulation::class);
    }

    public function save(HdxPopulation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HdxPopulation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HdxPopulation[] Returns an array of HdxPopulation objects
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

//    public function findOneBySomeField($value): ?HdxPopulation
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
