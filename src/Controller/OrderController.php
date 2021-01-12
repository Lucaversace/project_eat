<?php

namespace App\Controller;

use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/Commande", name="order")
     */
    public function index(OrderService $orderService): Response
    {
        $order = $orderService->createOrder();
        
        return $this->render('order/index.html.twig', [
            'order' => $order,
        ]);
    }
}
