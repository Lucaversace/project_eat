<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
use App\Service\Payment\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/Commande", name="order")
     */
    public function index(OrderService $orderService, CartService $cartService, PaymentService $paymentService): Response
    {
        $order = $orderService->createOrder();
        $priceOrder = $order->getPrice();
        $paymentService->debit($priceOrder);
        
        $items = $cartService->getFullCart();
        $total = $cartService->getTotal();
        return $this->render('order/index.html.twig', [
            'order' => $order,
            'items' => $items,
            'total' => $total
        ]);
    }
}
