<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\KeyFiguresRepository;

class KeyFiguresYearsStateProvider implements ProviderInterface
{

    protected KeyFiguresRepository $repository;

    public function __construct(KeyFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var \ApiPlatform\Metadata\GetCollection $operation */
        $operation = $context['operation'];
        $properties = $operation->getExtraProperties() ?? [];
        $provider = $properties['provider'] ?? NULL;

        return $this->repository->getDistinctYears($provider);
    }
}
