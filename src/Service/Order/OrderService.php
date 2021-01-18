<?php

namespace App\Service\Order;

use App\Entity\LineArticle;
use App\Entity\Order;
use App\Entity\StateOrder;
use App\Service\Cart\CartService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class OrderService{

    protected $cartService;
    protected $security;
    protected $feeDelevery = 2.5;
    protected $manager;


    public function __construct( CartService $cartService, Security $security, EntityManagerInterface $manager)
    {
        $this->cartService = $cartService;   
        $this->security = $security;
        $this->manager = $manager;
    }

    public function createOrder(): Order{

        $panier = $this->cartService->getFullCart();

        $user = $this->security->getUser();

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
            $totalOrder += $this->feeDelevery;

            $order->setPrice($totalOrder);
            $order->setRestaurant($product['product']->getRestaurant());
            $order->addLineArticle($lineArticle);
            $order->setStatus(StateOrder::IN_PROGRESS);
            $order->setDate(new DateTime());

        }
        return $order;
    }

    public function saveOrder($order){

        $this->manager->persist($order);
        $this->manager->flush($order);
    }

}
