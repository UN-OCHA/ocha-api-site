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

    /**
     * @return ReliefWebCrisisFigures[] Returns an array of FtsKeyFigures objects
     */
    public function findByIso3(string $value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.iso3 = :val')
            ->setParameter('val', $value)
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return string[] Returns an array of iso3 codes
     */
    public function getDistinctIso3(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.iso3', 'DESC')
            ->select('DISTINCT(f.iso3) as iso3')        
            ->getQuery()
            ->getScalarResult()
        ;
    }

}
