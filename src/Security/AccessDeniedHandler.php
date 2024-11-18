<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Service\RedirectService;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private RedirectService $redirectService) {}

    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $route = $request->getPathInfo();

        $type = '';
        $message = '';
        $fallbackRoute = '';

        if ($route === '/login') {
            $type = 'information';
            $message = 'access_denied.login';
            $fallbackRoute = 'app_home';
        }
        
        if (str_starts_with($route, '/admin')) {
            $type = 'information';
            $message = 'access_denied.login';
            $fallbackRoute = 'app_home';
        }

        return $fallbackRoute === '' ? new Response('access_denied', 403) : $this->redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
    }
}