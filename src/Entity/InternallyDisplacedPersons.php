<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\InternallyDisplacedPersonsRepository;
use App\State\KeyFiguresYearsStateProvider;
use App\State\KeyFiguresCountriesStateProvider;
use App\State\KeyFiguresStateProvider;

#[ORM\Entity(repositoryClass: InternallyDisplacedPersonsRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_IDPS')",
    operations: [
        new Get(
            controller: NotFoundAction::class, 
            read: false, 
            output: false,
            host: 'do-not-use',
        ),
        new GetCollection(
            normalizationContext: [
                'groups' => ['without_meta'],
                'skip_null_values' => FALSE,
            ],
            uriTemplate: '/idps',
            provider: KeyFiguresStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get Internally Displaced Persons key figures',
                'description' => 'Get Internally Displaced Persons key figures',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
            ]
        ),
        new GetCollection(
            uriTemplate: '/idps/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
            ),
        new GetCollection(
            uriTemplate: '/idps/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['iso3' => 'exact', 'year' => 'exact', 'source' => 'exact', 'tags' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['iso3' => 'ASC', 'year' => 'DESC', 'year' => 'ASC'])]
class InternallyDisplacedPersons extends KeyFigures
{

}
