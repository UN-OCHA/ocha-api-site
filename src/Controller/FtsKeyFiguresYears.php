<?php

namespace App\Controller;

use App\Entity\SimpleStringObject;
use App\Repository\FtsKeyFiguresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FtsKeyFiguresYears extends AbstractController
{
    protected FtsKeyFiguresRepository $repository;

    public function __construct(FtsKeyFiguresRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route(path: '/api/fts/years', name: 'fts_years')]
    public function __invoke(): Response
    {
        $years = array_map(function ($year) {
            return new SimpleStringObject($year['year'], $year['year']);
        }, $this->repository->getDistinctYears());
        return $this->json($years);
    }
}
