<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\ExternalLookup;
use App\Entity\KeyFigures;
use App\Entity\OchaPresence;
use App\Entity\OchaPresenceExternalId;
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
     * Find not persisted item.
     */
    public function findNotPersisted(string $id) : KeyFigures|null {
      if ($inserts = $this->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions()) {
        foreach ($inserts as $entity) {
          if ($entity instanceof KeyFigures) {
            if ($entity->getId() == $id) {
              return $entity;
            }
          }
        }
      }

      return null;
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

    /**
     * @return array Returns an array of ocha presences.
     */
    public function getDistinctOchaPresences($provider = NULL): array
    {
        $qb = $this->createQueryBuilder('kf')
            ->innerJoin(ExternalLookup::class, 'el', 'WITH', "el.external_id = JSON_UNQUOTE(JSON_EXTRACT(kf.extra, '$.external_id'))")
            ->innerJoin('el.ochaPresenceExternalIds', 'opei')
            ->innerJoin(OchaPresence::class, 'op', 'WITH', 'op.id = opei.OchaPresence')
            ->orderBy('op.name', 'ASC')
            ->select('DISTINCT(op.id) as value, op.name as label');

        if (!empty($provider)) {
            $qb->andWhere($qb->expr()->eq('opei.Provider', ':provider'))
                ->setParameter(':provider', $provider);
        }

        return $qb->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @return array Returns an array of years.
     */
    public function getDistinctOchaPresenceYears($provider, $ocha_presence_id): array
    {
        $qb = $this->createQueryBuilder('kf')
            ->innerJoin(ExternalLookup::class, 'el', 'WITH', "el.external_id = JSON_UNQUOTE(JSON_EXTRACT(kf.extra, '$.external_id'))")
            ->innerJoin('el.ochaPresenceExternalIds', 'opei')
            ->select('DISTINCT(opei.year) as value, opei.year as label');

        $qb->andWhere($qb->expr()->eq('opei.Provider', ':provider'))
            ->setParameter(':provider', $provider);

        $qb->andWhere($qb->expr()->eq('opei.OchaPresence', ':ocha_presence_id'))
            ->setParameter(':ocha_presence_id', $ocha_presence_id);

        return $qb->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @return array Returns an array of figures.
     */
    public function getOchaPresenceFigures($provider, $ocha_presence_id, $year): array
    {
        $qb = $this->createQueryBuilder('kf')
            ->innerJoin(ExternalLookup::class, 'el', 'WITH', "el.external_id = JSON_UNQUOTE(JSON_EXTRACT(kf.extra, '$.external_id'))")
            ->innerJoin('el.ochaPresenceExternalIds', 'opei')
            ->select('kf');

        $qb->andWhere($qb->expr()->eq('opei.Provider', ':provider'))
            ->setParameter(':provider', $provider);

        $qb->andWhere($qb->expr()->eq('opei.OchaPresence', ':ocha_presence_id'))
            ->setParameter(':ocha_presence_id', $ocha_presence_id);

        $qb->andWhere($qb->expr()->eq('opei.year', ':year'))
            ->setParameter(':year', $year);

        return $qb->getQuery()
            ->getArrayResult()
        ;
    }

}
