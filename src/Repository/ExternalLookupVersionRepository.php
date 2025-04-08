<?php

namespace App\Repository;

use App\Entity\ExternalLookup;
use App\Entity\ExternalLookupVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExternalLookup>
 *
 * @method ExternalLookup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalLookup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalLookup[]    findAll()
 * @method ExternalLookup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalLookupVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalLookup::class);
    }

    public function save(ExternalLookup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExternalLookup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return \App\Entity\ExternalLookupVersion[]
     */
    public function getVersions(string $id): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addEntityResult(ExternalLookupVersion::class, 'v');

        $rsm->addFieldResult('v', 'id', 'id');
        $rsm->addFieldResult('v', 'provider', 'provider');
        $rsm->addFieldResult('v', 'year', 'year');
        $rsm->addFieldResult('v', 'iso3', 'iso3');
        $rsm->addFieldResult('v', 'external_id', 'externalId');
        $rsm->addFieldResult('v', 'name', 'name');
        $rsm->addFieldResult('v', 'version', 'version');
        $rsm->addFieldResult('v', 'ts', 'ts');
        $rsm->addFieldResult('v', 'deleted', 'deleted');

        $query = $this->getEntityManager()->createNativeQuery('SELECT * FROM external_lookup_version v WHERE v.id = ? ORDER BY v.ts DESC, v.version DESC', $rsm);
        $query->setParameter(1, $id);

        return $query->getArrayResult();
    }

}
