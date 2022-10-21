<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\SimpleStringObject;
use App\Repository\InternallyDisplacedPersonsRepository;

class InternallyDisplacedPersonsCountriesStateProvider implements ProviderInterface
{

    protected InternallyDisplacedPersonsRepository $repository;

    public function __construct(InternallyDisplacedPersonsRepository $repository)
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
