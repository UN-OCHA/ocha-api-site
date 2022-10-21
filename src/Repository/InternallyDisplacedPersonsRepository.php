<?php

namespace App\Repository;

use App\Entity\InternallyDisplacedPersons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InternallyDisplacedPersons>
 *
 * @method InternallyDisplacedPersons|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternallyDisplacedPersons|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternallyDisplacedPersons[]    findAll()
 * @method InternallyDisplacedPersons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternallyDisplacedPersonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternallyDisplacedPersons::class);
    }

    public function save(InternallyDisplacedPersons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InternallyDisplacedPersons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return InternallyDisplacedPersons[] Returns an array of InternallyDisplacedPersons objects
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
