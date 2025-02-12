<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/Panier", name="cart_index")
     */
    public function index(CartService $cartService, OrderService $orderService): Response
    {   
        $order = $orderService->createOrder();
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'order' => $order
        ]);
    }

    /**
     * @Route("/cart/add/{id}/{idResto}", name="cart_add")
     */
    public function add($id, $idResto, CartService $cartService): Response
    {
        $cartService->add($id);

        return $this->redirectToRoute('restaurant', ['id' => $idResto ]);
        
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute("cart_index");
    }
}
