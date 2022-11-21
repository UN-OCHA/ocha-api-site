<?php

namespace App\Repository;

use App\Entity\N8nWorkflow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<N8nWorkflow>
 *
 * @method N8nWorkflow|null find($id, $lockMode = null, $lockVersion = null)
 * @method N8nWorkflow|null findOneBy(array $criteria, array $orderBy = null)
 * @method N8nWorkflow[]    findAll()
 * @method N8nWorkflow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class N8nWorkflowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, N8nWorkflow::class);
    }

    public function save(N8nWorkflow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(N8nWorkflow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return N8nWorkflow[] Returns an array of N8nWorkflow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?N8nWorkflow
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
