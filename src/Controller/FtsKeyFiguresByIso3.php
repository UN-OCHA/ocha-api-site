<?php

namespace App\Controller;

use App\Entity\FtsKeyFigures;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FtsKeyFiguresByIso3 extends AbstractController
{
    public function __construct(
    ) {}

    #[Route(
        path: '/fts/country/{iso3}',
        name: 'fts_key_figures_iso3',
        defaults: [
            '_api_resource_class' => FtsKeyFigures::class,
            '_api_operation_name' => '_api_/fts/country/{iso3}',
        ],
        methods: ['GET'],
    )]
    public function __invoke(string $iso3)
    {
    }
}