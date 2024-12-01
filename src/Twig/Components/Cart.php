<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Service\RedirectService;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProductRepository;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Enum\ProductStatus;

#[AsLiveComponent]
final class Cart
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public array $cart;

    #[LiveProp]
    public int $totalPrice;

    public int $maxCartQuantity;

    public function __construct(private RequestStack $requestStack, private ParameterBagInterface $params, private ProductRepository $productRepository)
    {
        $this->maxCartQuantity = $this->params->get('app.max_cart_quantity');
    }

    public function mount(): void
    {
        $this->cart = $this->getCart();
        $this->totalPrice = $this->getTotalPrice();
    }

    private function getCart(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $productIds = array_keys($cart);

        if (empty($productIds)) {
            return [];
        }

        $products = $this->productRepository->getByIds($productIds);
        $cartWithProducts = [];

        foreach ($products as $product) {
            $productId = $product->getId();
            $quantity = $cart[$product->getId()] ?? 1;

            $cartItem['id'] = $productId;
            $cartItem['name'] = $product->getName();
            $cartItem['price'] = $product->getPrice();
            $cartItem['stock'] = $product->getStock();
            $cartItem['imageUrl'] = $product->getImage()->getUrl();
            $cartItem['quantity'] = $quantity;

            $cartWithProducts[$productId] = $cartItem;
        }

        return $cartWithProducts;
    }

    private function getTotalPrice(): int
    {
        $total = 0;

        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    #[LiveListener('updateProductQuantity')]
    public function updateCart(#[LiveArg] int $id, #[LiveArg] int $quantity): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$id]) && isset($this->cart[$id])) {
            if ($quantity === 0) {
                unset($cart[$id]);
                unset($this->cart[$id]);
            } else {
                $cart[$id] = $quantity;
                $this->cart[$id]['quantity'] = $quantity;
            }

            $session->set('cart', $cart);
            $this->totalPrice = $this->getTotalPrice();

            $this->emit('cartUpdated');
        }
    }

    #[LiveAction]
    public function payCart(Request $request, EntityManagerInterface $em, TokenInterface $token, RedirectService $redirectService): Response
    {
        $user = $token->getUser();

        if (!$user) {
            return $redirectService->redirectWithFlash($request, 'error', 'flash.cart.payment_user_required', 'app_login');
        }

        if (empty($this->cart)) {
            return $redirectService->redirectWithFlash($request, 'information', 'label.cart.empty', 'app_products_list');
        }

        $order = new Order();
        $order->setReference(uniqid('ORD-'));
        $order->setCreatedAt(new \DateTime());
        $order->setStatus(OrderStatus::IN_PREPARATION);
        $order->setUser($user);

        $productIds = array_keys($this->cart);
        $products = $this->productRepository->getByIds($productIds);

        foreach ($products as $product) {
            $quantity = $this->cart[$product->getId()]['quantity'];

            if ($product->getStock() < $quantity) {
                return $redirectService->redirectWithFlash($request, 'error', 'flash.cart.stock_insufficient', 'app_products_list');
            }

            $orderItem = new OrderItem();
            $orderItem->setQuantity($quantity);
            $orderItem->setProductPrice($product->getPrice());
            $orderItem->setProduct($product);
            $orderItem->setOrder($order);

            $order->addOrderItem($orderItem);

            $product->setStock($product->getStock() - $quantity);

            if ($product->getStock() === 0) {
                $product->setStatus(ProductStatus::SOLD_OUT);
            }

            $em->persist($orderItem);
            $em->persist($product);
        }

        $em->persist($order);
        $em->flush();

        $session = $this->requestStack->getSession();
        $session->set('cart', []);

        return $redirectService->redirectWithFlash($request, 'success', 'flash.cart.payment_processed', 'app_products_list');
    }
}
