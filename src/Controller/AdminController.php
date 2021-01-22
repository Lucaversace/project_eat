<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Repository\RestorerRepository;
use App\Repository\UserClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/Admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/Index", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

        /**
     * @Route("/Restaurant", name="restorer_index", methods={"GET"})
     */
    public function restorers(RestorerRepository $restorerRepository): Response
    {
        return $this->render('restorer/index.html.twig', [
            'restorers' => $restorerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Utilisateurs", name="user_client_index", methods={"GET"})
     */
    public function users(UserClientRepository $userClientRepository): Response
    {
        return $this->render('user_client/index.html.twig', [
            'user_clients' => $userClientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_client_show", methods={"GET"})
     */
    public function show(UserClient $userClient): Response
    {
        return $this->render('user_client/show.html.twig', [
            'user_client' => $userClient,
        ]);
    }
}
