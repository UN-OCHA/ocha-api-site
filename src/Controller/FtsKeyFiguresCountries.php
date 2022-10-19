<?php

namespace App\Controller;

use App\Repository\FtsKeyFiguresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FtsKeyFiguresCountries extends AbstractController
{
    protected FtsKeyFiguresRepository $repository;

    public function __construct(FtsKeyFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route(path: '/api/fts/countries', name: 'fts_countries')]
    public function __invoke(): Response
    {
        $countries = array_map(function ($iso3) {
            return $iso3['iso3'];
        }, $this->repository->getDistinctCountries());
        return $this->json($countries);
    }
}
