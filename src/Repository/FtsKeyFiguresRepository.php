<?php

namespace App\Repository;

use App\Entity\FtsKeyFigures;

/**
 * @extends ServiceEntityRepository<FtsKeyFigures>
 *
 * @method FtsKeyFigures|null find($id, $lockMode = null, $lockVersion = null)
 * @method FtsKeyFigures|null findOneBy(array $criteria, array $orderBy = null)
 * @method FtsKeyFigures[]    findAll()
 * @method FtsKeyFigures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FtsKeyFiguresRepository extends KeyFiguresRepository
{
}
