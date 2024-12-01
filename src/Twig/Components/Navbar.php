<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsLiveComponent]
final class Navbar
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $cartCount = 0;

    public function __construct(private RequestStack $requestStack) {}

    public function mount(): void
    {
        $this->updateCartCount();
    }

    #[LiveListener('cartUpdated')]
    public function updateCartCount(): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        $this->cartCount = array_sum($cart);
    }
}
