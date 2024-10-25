<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PianoController extends AbstractController
{
    #[Route('/piano', name: 'app_piano')]
    public function index(): Response
    {
        return $this->render('piano/index.html.twig', [
            
        ]);
    }
}
