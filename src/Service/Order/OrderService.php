<?php

namespace App\Service\Order;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrderService{

    protected $session;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->session  =  $sessionInterface;
    }

    public function createOrder(){

        $session = $this->session;

        $order = new Order();

    }

}
