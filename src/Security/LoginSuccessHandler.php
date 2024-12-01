<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        $route = $user instanceof User && $user->hasRole('ROLE_ADMIN') ? 'app_admin_dashboard' : 'app_home';

        return new RedirectResponse($this->router->generate($route));
    }
}
