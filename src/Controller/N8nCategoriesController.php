<?php

namespace App\Controller;

use App\Repository\N8nCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class N8nCategoriesController extends AbstractController
{

    public function __construct(private N8nCategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        return ['categories' => $this->repo->findAll()];
    }
}
