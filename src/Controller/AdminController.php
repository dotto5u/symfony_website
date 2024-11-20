<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\ChartService;
use App\Service\DashboardChartService;
use App\Service\PaginationService;
use App\Service\RedirectService;
use App\Form\ProductFormType;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository, ChartService $chartService, DashboardChartService $dashboardChartService, TranslatorInterface $translator): Response {
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

    #[Route('/admin/users/list', name: 'app_admin_users_list')]
    public function userList(Request $request, UserRepository $userRepository, PaginationService $paginationService): Response 
    {
        // TODO

        return $this->render('admin/users/list.html.twig', [
            
        ]);
    }

    #[Route('/admin/orders/list', name: 'app_admin_orders_list')]
    public function orderList(Request $request, OrderRepository $orderRepository, PaginationService $paginationService): Response 
    {
        // TODO

        return $this->render('admin/orders/list.html.twig', [
            
        ]);
    }

    #[Route('/admin/products/list', name: 'app_admin_products_list')]
    public function productList(Request $request, ProductRepository $productRepository, PaginationService $paginationService): Response 
    {
        $query = $productRepository->getAll(true);
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('admin/products/list.html.twig', [
            'productPagination' => $pagination,
        ]);
    }

    #[Route('/admin/products/add', name: 'app_admin_products_add')]
    public function productAdd(Request $request, EntityManagerInterface $em, RedirectService $redirectService): Response
    {   
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $type = 'success';
            $message = 'flash.product.added';
            $fallbackRoute = 'app_admin_products_list';
     
            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        return $this->render('admin/products/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/products/{id}/edit', name: 'app_admin_products_edit')]
    public function productEdit(int $id, Request $request, ProductRepository $productRepository, RedirectService $redirectService): Response
    {
        $product = $productRepository->getById($id);

        if ($product === null) {
            $type = 'error';
            $message = 'flash.product.not_found';
            $fallbackRoute = 'app_admin_products_list';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        return $this->render('admin/products/edit.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/admin/products/{id}/delete', name: 'app_admin_products_delete')]
    public function productDelete(int $id, Request $request, ProductRepository $productRepository, RedirectService $redirectService): Response
    {   
        $product = $productRepository->getById($id);

        $type = 'error';
        $message = '';
        $fallbackRoute = 'app_admin_products_list';

        if ($product === null) {
            $message = 'flash.product.not_found';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        // TODO

        $message = 'flash.product.cannot_be_deleted';

        return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
    }
}