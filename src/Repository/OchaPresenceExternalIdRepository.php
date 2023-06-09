<?php

namespace App\Repository;

use App\Entity\OchaPresenceExternalId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OchaPresenceExternalId>
 *
 * @method OchaPresenceExternalId|null find($id, $lockMode = null, $lockVersion = null)
 * @method OchaPresenceExternalId|null findOneBy(array $criteria, array $orderBy = null)
 * @method OchaPresenceExternalId[]    findAll()
 * @method OchaPresenceExternalId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OchaPresenceExternalIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OchaPresenceExternalId::class);
    }

    public function save(OchaPresenceExternalId $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OchaPresenceExternalId $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
