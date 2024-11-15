<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Service\ChartService;
use App\Service\DashboardChartService;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\PaginationService;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        ProductRepository $productRepository,  
        OrderRepository $orderRepository, 
        ChartService $chartService, 
        DashboardChartService $dashboardChartService, 
        TranslatorInterface $translator
    ): Response {
        $productCount = $productRepository->getProductCountByCategory();
        $lastFiveOrders = $orderRepository->getLastFiveOrders();
        $availabilityRatio = $productRepository->getAvailabilityRatio();
        $salesOverLastTwelveMonths = $orderRepository->getSalesOverLastTwelveMonths();

        $pieChart = $dashboardChartService->preparePieChart($availabilityRatio, $chartService, $translator);
        $barChart = $dashboardChartService->prepareBarChart($salesOverLastTwelveMonths, $chartService, $translator);

        return $this->render('admin/dashboard.html.twig', [
            'productCount' => $productCount,
            'lastFiveOrders' => $lastFiveOrders,
            'pieChart' => $pieChart,
            'barChart' => $barChart,
        ]);
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function products(Request $request, ProductRepository $productRepository, PaginationService $paginationService): Response {
        $query = $productRepository->getAll(true);
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('products/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
