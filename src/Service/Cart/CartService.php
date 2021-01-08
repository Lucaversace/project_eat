<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService{

    protected $session;
    protected $platRepository;

    function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function add(int $id){
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

    }

    public function remove(int $id){

    }

    public function getFullCart():array{

    }

    public function getTotal(): float{

    }

}
