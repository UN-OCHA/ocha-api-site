<?php

namespace App\Repository;

use App\Entity\InternallyDisplacedPersonsValues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InternallyDisplacedPersonsValues>
 *
 * @method InternallyDisplacedPersonsValues|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternallyDisplacedPersonsValues|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternallyDisplacedPersonsValues[]    findAll()
 * @method InternallyDisplacedPersonsValues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternallyDisplacedPersonsValuesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternallyDisplacedPersonsValues::class);
    }

    public function save(InternallyDisplacedPersonsValues $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InternallyDisplacedPersonsValues $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InternallyDisplacedPersonsValues[] Returns an array of InternallyDisplacedPersonsValues objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InternallyDisplacedPersonsValues
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
