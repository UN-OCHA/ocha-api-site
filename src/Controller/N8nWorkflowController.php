<?php

namespace App\Controller;

use App\Repository\N8nWorkflowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class N8nWorkflowController extends AbstractController
{

    public function __construct(private N8nWorkflowRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($id)
    {
        return $this->repo->findOneBy(['id' => $id]);
    }
}
