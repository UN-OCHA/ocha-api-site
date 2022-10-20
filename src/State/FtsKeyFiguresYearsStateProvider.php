<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\SimpleStringObject;
use App\Repository\FtsKeyFiguresRepository;

class FtsKeyFiguresYearsStateProvider implements ProviderInterface
{

    protected FtsKeyFiguresRepository $repository;

    public function __construct(FtsKeyFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $years = array_map(function ($year) {
            return new SimpleStringObject($year['year'], $year['year']);
        }, $this->repository->getDistinctYears());

        return $years;
    }
}
