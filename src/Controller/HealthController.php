<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class HealthController extends AbstractController
{
    #[Route(
        name: 'health',
        path: '/api/v1/n8n/health',
        methods: ['GET'],
    )]    
    public function __invoke()
    {
        return new Response(json_encode(
            [
                'status' => 'ok',
            ]
            ));
    }
}
