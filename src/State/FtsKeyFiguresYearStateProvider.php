<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\FtsKeyFiguresRepository;

class FtsKeyFiguresYearStateProvider implements ProviderInterface
{

    protected FtsKeyFiguresRepository $repository;

    public function __construct(FtsKeyFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->repository->findByYear($uriVariables['year']);
    }
}