<?php 

namespace App\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    public function home(): Response
    {
        return $this->render('home.html.twig', []);
    }
}