<?php
namespace App\Service\Payment;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaymentService{

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    public function debit(){

        

    }
}