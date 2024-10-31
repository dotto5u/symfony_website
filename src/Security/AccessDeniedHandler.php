<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $route = $request->getPathInfo();
        $session = $request->getSession();
        $flashBag = $session instanceof Session ? $session->getFlashBag() : null;

        if ($flashBag) {
            if ($route === '/login') {
                $flashBag->add('information', 'access_denied.login');
                return new RedirectResponse($this->router->generate('app_home'));
            }

            if (str_starts_with($route, '/admin')) {
                $flashBag->add('error', 'access_denied.admin');
                return new RedirectResponse($this->router->generate('app_home'));
            }
        }

        return new Response('access_denied', 403);
    }
}