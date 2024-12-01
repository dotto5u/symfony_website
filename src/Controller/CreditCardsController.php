<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreditCardsController extends AbstractController
{
    #[Route('/credit-cards', name: 'app_credit_cards')]
    public function index(): Response
    {
        return $this->render('credit_cards.html.twig');
    }
}