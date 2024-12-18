<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Service\RedirectService;

class ProductsController extends AbstractController
{
    #[Route('/products/list', name: 'app_products_list')]
    public function list(): Response
    {
        return $this->render('products/list.html.twig');
    }

    #[Route('/products/{id}/view', name: 'app_products_view')]
    public function view(int $id, Request $request, ProductRepository $productRepository, RedirectService $redirectService): Response
    {
        $product = $productRepository->getById($id);

        if ($product === null) {
            $type = 'error';
            $message = 'flash.product.not_found';
            $fallbackRoute = 'app_products_list';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        $referer = $request->headers->get('referer');

        $backRouteName = match (true) {
            $referer && str_contains($referer, '/admin') => 'app_admin_products_list',
            $referer && str_contains($referer, '/cart') => 'app_cart',
            default => 'app_products_list',
        };

        return $this->render('products/view.html.twig', [
            'product' => $product,
            'backRouteName' => $backRouteName,
        ]);
    }
}
