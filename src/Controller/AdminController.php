<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository, ChartService $chartService, DashboardChartService $dashboardChartService, TranslatorInterface $translator): Response
    {
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
        $query = $userRepository->getAll(true);
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('admin/users/list.html.twig', [
            'userPagination' => $pagination,
        ]);
    }

    #[Route('/admin/orders/list', name: 'app_admin_orders_list')]
    public function orderList(Request $request, OrderRepository $orderRepository, PaginationService $paginationService): Response
    {
        $query = $orderRepository->getAll(true);
        $pagination = $paginationService->paginate($request, $query, 5);

        return $this->render('admin/orders/list.html.twig', [
            'orderPagination' => $pagination,
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
        $isEdit = false;

        $form = $this->createForm(ProductFormType::class, $product, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $type = 'success';
            $message = 'flash.product.added';
            $fallbackRoute = 'app_admin_products_list';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        return $this->render('admin/products/form.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
        ]);
    }

    #[Route('/admin/products/{id}/edit', name: 'app_admin_products_edit')]
    public function productEdit(int $id, Request $request, RequestStack $requestStack, EntityManagerInterface $em, ProductRepository $productRepository, RedirectService $redirectService): Response
    {
        $type = '';
        $message = '';
        $fallbackRoute = 'app_admin_products_list';

        $product = $productRepository->getById($id);

        if ($product === null) {
            $type = 'error';
            $message = 'flash.product.not_found';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        $isEdit = true;

        $form = $this->createForm(ProductFormType::class, $product, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $requestStack->getSession();
            $cart = $session->get('cart', []);
            $productId = $product->getId();
            $productStock = $product->getStock();

            if (isset($cart[$productId])) {
                $cartQuantity = $cart[$productId];

                if ($cartQuantity > $productStock) {
                    $cart[$productId] = $productStock;
                }

                if ($productStock === 0) {
                    unset($cart[$productId]);
                }

                $session->set('cart', $cart);
            }

            $em->persist($product);
            $em->flush();

            $type = 'success';
            $message = 'flash.product.updated';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        return $this->render('admin/products/form.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
        ]);
    }

    #[Route('/admin/products/{id}/delete', name: 'app_admin_products_delete')]
    public function productDelete(int $id, Request $request, RequestStack $requestStack, EntityManagerInterface $em, ProductRepository $productRepository, RedirectService $redirectService): Response
    {
        $type = '';
        $message = '';
        $fallbackRoute = 'app_admin_products_list';

        $product = $productRepository->getById($id);

        if ($product === null) {
            $type = 'error';
            $message = 'flash.product.not_found';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        if (!$product->getOrderItems()->isEmpty()) {
            $type = 'error';
            $message = 'flash.product.cannot_be_deleted_due_to_order';

            return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
        }

        try {
            $session = $requestStack->getSession();
            $cart = $session->get('cart', []);
            $productId = $product->getId();

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                $session->set('cart', $cart);
            }

            $product->removeAllCategories();
            $em->flush();

            $em->remove($product);
            $em->flush();

            $type = 'success';
            $message = 'flash.product.deleted';
        } catch (\Exception) {
            $type = 'error';
            $message = 'flash.product.cannot_be_deleted';
        }

        return $redirectService->redirectWithFlash($request, $type, $message, $fallbackRoute);
    }
}
