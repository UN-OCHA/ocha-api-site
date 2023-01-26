<?php

namespace App\Repository;

use App\Entity\KeyFigures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KeyFigures>
 *
 * @method KeyFigures|null find($id, $lockMode = null, $lockVersion = null)
 * @method KeyFigures|null findOneBy(array $criteria, array $orderBy = null)
 * @method KeyFigures[]    findAll()
 * @method KeyFigures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeyFiguresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeyFigures::class);
    }

    public function save(KeyFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(KeyFigures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return KeyFigures[]
     */
    public function get($provider = NULL): array
    {
        $qb = $this->createQueryBuilder('f');

        if (!empty($provider)) {
            $qb->where($qb->expr()->eq('f.provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int[] Returns an array of years
     */
    public function getDistinctYears($provider = NULL): array
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.year', 'DESC')
            ->select('DISTINCT(f.year) as value, f.year as label');

        if (!empty($provider)) {
            $qb->where($qb->expr()->eq('f.provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

        return $qb
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @return array Returns an array of iso3
     */
    public function getDistinctCountries($provider = NULL): array
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('value', 'ASC')
            ->select('DISTINCT(LOWER(f.iso3)) as value, f.country as label');

        if (!empty($provider)) {
            $qb->where($qb->expr()->eq('f.provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

        return $qb->getQuery()
            ->getArrayResult()
        ;
    }

}
