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
    public function index(CartService $cartService, OrderService $orderService): Response
    {
        $order = $orderService->createOrder();
        $items = $cartService->getFullCart();
        $total = $cartService->getTotal();
        return $this->render('order/index.html.twig', [

            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * @Route("/Paiement", name="payment")
     */
    public function payment(OrderService $orderService, PaymentService $paymentService, CartService $cartService){

        $order = $orderService->createOrder();
        $priceOrder = $order->getPrice();
        
        if($paymentService->debit($priceOrder)){
            $orderService->saveOrder($order);
            $cartService->resetCart();
            $pathButton = "accueil";
            $textButton= "Retour à l'accueil";
            $imgSrc = "/img/check.png";
            $this->addFlash(
                "success",
                "Votre paiement à bien été pris en compte, votre commande est en cours ! "
            );
        }else{
            $pathButton = "user_client_solde";
            $textButton= "Modifier mon solde";
            $imgSrc = "/img/false.png";
             $this->addFlash(
                "danger",
                "Désolé, vous n'avez pas le solde suffisant pour payer la commande."
            );
        }

        return $this->render('order/payment.html.twig', [
            'pathButton' => $pathButton,
            'textButton' => $textButton,
            'imgSrc' => $imgSrc
        ]);
    }
}
