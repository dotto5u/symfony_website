<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PaginationService;
use App\Repository\ProductRepository;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', []);
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function products(Request $request, ProductRepository $productRepository, PaginationService $paginationService): Response
    {
        $query = $productRepository->getAllQuery();
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('admin/products/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
