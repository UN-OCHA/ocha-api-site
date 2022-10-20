<?php

namespace App\Repository;

use App\Entity\FtsKeyFigures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FtsKeyFigures>
 *
 * @method FtsKeyFigures|null find($id, $lockMode = null, $lockVersion = null)
 * @method FtsKeyFigures|null findOneBy(array $criteria, array $orderBy = null)
 * @method FtsKeyFigures[]    findAll()
 * @method FtsKeyFigures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FtsKeyFiguresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FtsKeyFigures::class);
    }

    public function save(FtsKeyFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FtsKeyFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return FtsKeyFigures[] Returns an array of FtsKeyFigures objects
     */
    public function findByIso3(string $value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.iso3 = :val')
            ->setParameter('val', $value)
            ->orderBy('f.planId', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return FtsKeyFigures[] Returns an array of FtsKeyFigures objects
     */
    public function findByYear(string $value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.year = :val')
            ->setParameter('val', $value)
            ->orderBy('f.planId', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int[] Returns an array of years
     */
    public function getDistinctYears(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.year', 'DESC')
            ->select('DISTINCT(f.year) as year')
            ->getQuery()
            ->getScalarResult()
        ;
    }

    /**
     * @return string[] Returns an array of iso3
     */
    public function getDistinctCountries(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.iso3', 'ASC')
            ->select('DISTINCT(f.iso3) as iso3, f.country')
            ->getQuery()
            ->getScalarResult()
        ;
    }

}
