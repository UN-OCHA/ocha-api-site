<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\SimpleStringObject;
use App\Repository\ReliefWebCrisisFiguresRepository;

class ReliefWebCrisisFiguresCountriesStateProvider implements ProviderInterface
{

    protected ReliefWebCrisisFiguresRepository $repository;

    public function __construct(ReliefWebCrisisFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $countries = array_map(function ($iso3) {
            return new SimpleStringObject($iso3['iso3'], $iso3['country']);
        }, $this->repository->getDistinctCountries());
        
        return $countries;
    }
}
