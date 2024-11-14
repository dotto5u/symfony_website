<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\PaginationService;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Service\ChartService;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository, ChartService $chartService, TranslatorInterface $translator): Response
    {
        $productCount = $productRepository->getProductCountByCategory();
        $orders = $orderRepository->getLastFiveOrders();
        $availabilityRatio = $productRepository->getAvailabilityRatio();
        
        $pieChartLabels = array_keys($availabilityRatio);
        $pieChartLabelsTrans = array_map(function($label) use ($translator)
        {
            return $translator->trans('label.'.$label); 
        }, 
        $pieChartLabels);
        $pieChartData = array_values($availabilityRatio);
        $pieChartBackgroundColor = ['#2E7D32', '#0288D1', '#C62828'];

        $barChartLabels = ['Nov 2024', 'Oct 2024', 'Sep 2024', 'Aug 2024', 'Jul 2024', 'Jun 2024', 'May 2024', 'Apr 2024', 'Mar 2024', 'Feb 2024', 'Jan 2024', 'Dec 2023'];
        $barChartData = [4800, 15200, 11800, 34500, 22100, 17800, 14200, 32700, 15800, 45000, 39000, 28300];
        $barChartBackgroundColor = [
            '#A8D5BA', '#2E7D32','#B3E5FC', '#0288D1', '#FFCDD2', '#C62828',
            '#00BCD4', '#A8D5BA', '#2E7D32', '#B3E5FC','#0288D1','#FFCDD2'
        ];

        $pieChart = $chartService->createPieChart($pieChartLabelsTrans, $pieChartData, $pieChartBackgroundColor);
        $barChart = $chartService->createBarChart($barChartLabels, $barChartData, $barChartBackgroundColor);

        return $this->render('admin/dashboard.html.twig', [
            'productCount' => $productCount,
            'orders' => $orders,
            'pieChart' => $pieChart,
            'barChart' => $barChart,
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
