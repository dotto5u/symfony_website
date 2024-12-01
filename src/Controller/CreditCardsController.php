<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Repository\CreditCardRepository;

class CreditCardsController extends AbstractController
{
    public function __construct() {}

    #[Route('/credit-cards', name: 'app_credit_cards')]
    public function index(TokenInterface $token, CreditCardRepository $creditCardRepository): Response
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $userId = $user->getId();

        $creditCards = $creditCardRepository->getByUser($userId);
        $creditCardsHydrated = [];

        foreach($creditCards as $creditCard) {
            $creditCardId = $creditCard->getId();

            $item['id'] = $creditCardId;
            $item['number'] = $creditCard->getNumber();
            $item['expirationDate'] = $creditCard->getExpirationDate()->format('d/m/y');
            $item['cvv'] = $creditCard->getCvv();

            $creditCardsHydrated[$creditCardId] = $item;
        }

        return $this->render('credit_cards.html.twig', [
            'userId' => $userId,
            'creditCardsHydrated' => $creditCardsHydrated,
        ]);
    }
}