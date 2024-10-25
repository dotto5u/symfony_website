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
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        $form = $this->createForm(LoginFormType::class, [
            'username' => $lastUsername
        ]);

        if ($this->getUser()) {
            $user = $this->getUser();
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_admin');
            }
    
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {

    }
}
