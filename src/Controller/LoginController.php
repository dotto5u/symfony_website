<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginFormType::class);
        $form->get('email')->setData($authenticationUtils->getLastUsername());
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('login.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {
        // logout géré par le security.yaml
    }
}