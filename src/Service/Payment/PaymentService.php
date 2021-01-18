<?php
namespace App\Service\Payment;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PaymentService{

    protected $security;
    protected $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->manager = $manager;
    }
    
    public function debit(float $total): bool{

        $user = $this->security->getUser();
        $userWallet = $user->getWallet();

        if($this->verifDebit($total, $userWallet)){
            $newWallet = $userWallet - $total;
            $user->setWallet($newWallet);
            $this->manager->flush($user);
            return  true;
        }
        else{
            return false;
        }

    }

    public function verifDebit($total, $userWallet): bool{
        if($userWallet >= $total){
            return true;
        }else{
            return false;
        }
        
    }
}