<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    #[Route('/app/', name: 'home')]
    #[Route('/app/{route}', name: 'vue_pages', requirements: ['route' => "^.+"])]
    public function index(): Response
    {
        return $this->render('app.html.twig');
    }

}
