<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Product;
use App\Enum\ProductStatus;

#[AsLiveComponent]
final class ProductView
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public Product $product;

    #[LiveProp]
    public string $backRouteName;

    private int $maxCartQuantity;

    public function __construct(private RequestStack $requestStack, private ParameterBagInterface $params)
    {
        $this->maxCartQuantity = $this->params->get('app.max_cart_quantity');
    }

    #[LiveAction]
    public function addToCart(): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        $productId = $this->product->getId();
        $quantity = $cart[$productId] ?? 0;

        if ($quantity < $this->product->getStock()) {
            $cart[$productId] = $quantity + 1;

            $session->set('cart', $cart);

            $this->emit('cartUpdated');
        }
    }

    public function isAddToCardAvailable(): bool
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        $productId = $this->product->getId();
        $productStatus = $this->product->getStatus();
        $productStock = $this->product->getStock();
        $quantity = $cart[$productId] ?? 0;
        $maxToCart = $productStock > $this->maxCartQuantity ? $this->maxCartQuantity : $productStock;

        return $quantity < $maxToCart && $productStatus->value === ProductStatus::AVAILABLE->value;
    }
}
