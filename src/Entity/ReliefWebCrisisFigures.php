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
use App\Repository\ReliefWebCrisisFiguresRepository;
use App\State\KeyFiguresYearsStateProvider;
use App\State\KeyFiguresCountriesStateProvider;
use App\State\KeyFiguresStateProvider;

#[ORM\Entity(repositoryClass: ReliefWebCrisisFiguresRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RW_CRISIS')",
    operations: [
        new Get(
            controller: NotFoundAction::class, 
            read: false, 
            output: false,
            host: 'do-not-use',
            options: ['disabled' => TRUE],
        ),
        new GetCollection(
            uriTemplate: '/relief_web_crisis_figures/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
            ),
        new GetCollection(
            uriTemplate: '/relief_web_crisis_figures/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        ),
        new GetCollection(
            normalizationContext: [
                'groups' => ['without_meta'],
                'skip_null_values' => FALSE,
            ],
            uriTemplate: '/relief_web_crisis_figures',
            provider: KeyFiguresStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list of ReliefWeb crisis figures',
                'description' => 'Get a list of ReliefWeb crisis figures',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
            ]
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['iso3' => 'exact', 'year' => 'exact', 'source' => 'exact', 'tags' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['iso3' => 'ASC', 'year' => 'DESC', 'year' => 'ASC'])]
class ReliefWebCrisisFigures extends KeyFigures
{

}
