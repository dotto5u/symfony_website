<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PaginationService;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository): Response
    {
        $productCount = $productRepository->getProductCountByCategory();
        $orders = $orderRepository->findLastFiveOrders();

        return $this->render('admin/dashboard.html.twig', [
            'productCount' => $productCount,
            'orders' => $orders,
        ]);
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function products(Request $request, ProductRepository $productRepository, PaginationService $paginationService): Response
    {
        $query = $productRepository->getAll(true);
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('products/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
