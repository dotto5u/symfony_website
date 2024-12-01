<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

class RedirectService
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function redirectWithFlash(Request $request, string $type, string $message, string $fallbackRoute): RedirectResponse
    {
        $session = $this->getSession($request);

        $flashBag = $this->getFlashBag($session);

        $flashBag->add($type, $message);

        $redirectUrl = $this->urlGenerator->generate($fallbackRoute);

        return new RedirectResponse($redirectUrl);
    }

    private function getSession(Request $request): SessionInterface
    {
        try {
            $session = $request->getSession();
        } catch (SessionNotFoundException $e) {
            throw new \LogicException('Sessions are disabled. Enable them in "config/packages/framework.yaml".', 0, $e);
        }

        return $session;
    }

    private function getFlashBag(SessionInterface $session): FlashBagInterface
    {
        if (!$session instanceof FlashBagAwareSessionInterface) {
            throw new \LogicException(sprintf('You cannot use flash messages because session "%s" does not implement "%s".', get_debug_type($session), FlashBagAwareSessionInterface::class));
        }

        return $session->getFlashBag();
    }
}
