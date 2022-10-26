<?php

namespace App\Repository;

use App\Entity\ReliefWebCrisisFigures;

/**
 * @extends ServiceEntityRepository<ReliefWebCrisisFigures>
 *
 * @method ReliefWebCrisisFigures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReliefWebCrisisFigures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReliefWebCrisisFigures[]    findAll()
 * @method ReliefWebCrisisFigures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReliefWebCrisisFiguresRepository extends FtsKeyFiguresRepository
{
}
