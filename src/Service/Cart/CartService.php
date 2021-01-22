<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\DishRepository;

class CartService{

    protected $session;
    protected $dishRepository;

    public function __construct(SessionInterface $session, DishRepository $dishRepository)
    {
        $this->session = $session;
        $this->dishRepository = $dishRepository;
    }

    public function add(int $id){

        $panier = $this->session->get('panier', []);

        $dishAdd = $this->dishRepository->find($id);
        $idResto = $dishAdd->getRestaurant()->getId();

        $verify = $this->verifyAdd($idResto);

        if($verify){

            if (!empty($panier[$id])){
                $panier[$id]++;
            } else{

                $panier[$id] = 1;
            }

            $this->session->set('panier', $panier);
        }else{
            $err  ="Impossible d'ajouter un produit d'un autre restaurant.";
        }
    }

    public function verifyAdd($idAddRestau): bool{

        $panierData = $this->getFullCart();

        if(empty($panierData)){
            return true;
        }
        else{
            $idRestauExist = $panierData[0]["product"]->getRestaurant()->getId();
            if($idRestauExist != $idAddRestau){
                return  false;
            }
            else{
                return true;
            }
        }

    }

    public function remove(int $id){
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
        
    }

    public function getFullCart():array{

        $panier  = $this->session->get('panier', []);
        $panierData =[];

        foreach ($panier as $id  => $quantity ){
            $panierData[] =[
                'product' => $this->dishRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierData;
    }

    public function getTotal(): float{
        $total = 0;
        $panierData = $this->getFullCart();

        foreach ($panierData as $item){
            $totalItem = $item['product']->getPrice()  * $item['quantity'];
            $total += $totalItem;
        }

        return  $total;
    }

    public function resetCart(){
        $this->session->set('panier', []);
    }

}
