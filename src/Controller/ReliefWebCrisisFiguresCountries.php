<?php

namespace App\Controller;

use App\Repository\ReliefWebCrisisFiguresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ReliefWebCrisisFiguresCountries extends AbstractController
{
    protected ReliefWebCrisisFiguresRepository $repository;

    public function __construct(ReliefWebCrisisFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route(path: '/api/relief_web_crisis_figures/countries', name: 'relief_web_crisis_figures_iso3')]
    public function __invoke(): Response
    {
        $iso3 = array_map(function ($iso3) {
            return $iso3['iso3'];
        }, $this->repository->getDistinctIso3());
        return $this->json($iso3);
    }
}
