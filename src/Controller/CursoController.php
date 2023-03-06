<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/curso')]
class CursoController extends AbstractController
{
    #[Route('/', name: 'curso_lista')]
    public function index(): Response
    {
        return $this->render('curso/index.html.twig', [
            'controller_name' => 'CursoController',
        ]);
    }
}
