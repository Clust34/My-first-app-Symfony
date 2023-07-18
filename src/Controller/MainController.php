<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('', name: 'app.homepage', methods: ['GET'])]
    public function index(): Response
    {
        $donnees = ['Pierre', 'Paul', 'Jacques'];

        return $this->render('Home/index.html.twig', [
            'donnees' => $donnees,
        ]);
    }
}
