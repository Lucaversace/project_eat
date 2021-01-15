<?php

namespace App\Service\Order;

use App\Entity\LineArticle;
use App\Entity\Order;
use App\Entity\StateOrder;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class OrderService{

    protected $cartService;
    protected $session;
    protected $security;

    public function __construct( CartService $cartService, SessionInterface $session, Security $security)
    {
        $this->cartService = $cartService;   
        $this->session = $session;
        $this->security = $security;
    }

    public function createOrder(): Order{

        $panier = $this->cartService->getFullCart();

        $user = $this->session->get('user');

        $order = new Order();
        $order->setUser($user);
        $totalOrder = 0;

        foreach($panier as $product){

            $lineArticle = new LineArticle();

            $price = $product['product']->getPrice();
            $quantity = $product['quantity'];

            $lineArticle->setDish($product['product']);
            $lineArticle->setQuantity($quantity);

            $priceLine = $price * $quantity;
            $lineArticle->setPrice($priceLine);
            $totalOrder += $priceLine;

            $order->setPrice($totalOrder);
            $order->setRestaurant($product['product']->getRestaurant());
            $order->addLineArticle($lineArticle);
            $order->setStatus(StateOrder::IN_PROGRESS);

        }
        return $order;
    }

}
