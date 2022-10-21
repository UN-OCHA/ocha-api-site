<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\InternallyDisplacedPersonsRepository;

class InternallyDisplacedPersonsIso3StateProvider implements ProviderInterface
{

    protected InternallyDisplacedPersonsRepository $repository;

    public function __construct(InternallyDisplacedPersonsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->repository->findByIso3($uriVariables['iso3']);
    }
}
