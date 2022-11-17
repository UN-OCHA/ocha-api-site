<?php

namespace App\Controller;

use App\Repository\N8nCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class N8nCollectionsController extends AbstractController
{

    public function __construct(private N8nCollectionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        return ['collections' => $this->repo->findAll()];
    }
}
