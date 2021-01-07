<?php

namespace App\Controller;

use App\Repository\PlatRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    

    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function plat(PlatRepository $platRepository): Response
    {
        $plats = $platRepository->findAll();
        return $this->render('index/index.html.twig', [
            'plats' => $plats,
        ]);
    }
    
    /**
   * @Route("/restaurant/{restaurant_id}", name="restaurant")
   */
  public function plat_id(PlatRepository $platRepository): Response
  {
    return $this->render('article/index.html.twig', [
      'controller_name' => 'IndexController',
      'plats' => $platRepository
    ]);
  }
}


