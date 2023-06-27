<?php

namespace App\Repository;

use App\Entity\OchaPresence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OchaPresence>
 *
 * @method OchaPresence|null find($id, $lockMode = null, $lockVersion = null)
 * @method OchaPresence|null findOneBy(array $criteria, array $orderBy = null)
 * @method OchaPresence[]    findAll()
 * @method OchaPresence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OchaPresenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OchaPresence::class);
    }

    public function save(OchaPresence $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OchaPresence $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
