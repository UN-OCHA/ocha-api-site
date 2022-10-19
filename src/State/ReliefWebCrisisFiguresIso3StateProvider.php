<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ReliefWebCrisisFiguresRepository;

class ReliefWebCrisisFiguresIso3StateProvider implements ProviderInterface
{

    protected ReliefWebCrisisFiguresRepository $repository;

    public function __construct(ReliefWebCrisisFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->repository->findByIso3($uriVariables['iso3']);
    }
}
