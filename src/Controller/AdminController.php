<?php

namespace App\Controller;

use App\Entity\Restorer;
use App\Entity\UserClient;
use App\Form\RestorerType;
use App\Form\UserClientType;
use App\Repository\OrderRepository;
use App\Repository\RestorerRepository;
use App\Repository\UserClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/Admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/TableauDeBord", name="dashboard")
     */
    public function dashboard(RestorerRepository $restorerRepository, OrderRepository $orderRepository): Response
    {
        $restorers = $restorerRepository->findAll();
        $nbRestorers = 0;
        foreach($restorers as $restorer){
            $nbRestorers ++;
        }
        $nbOrders = 0;
        $orders = $orderRepository->findAll();
        foreach($orders as $order){
            $nbOrders ++;
        }
        $nbOrdersFinished = 0;
        $ordersFinished = $orderRepository->findBy(['status' => 'LIVRÉE']);
        foreach($ordersFinished as $orderFinished){
            $nbOrdersFinished ++;
        }

        $benefits  = $nbOrders * 2.5;
         
        return $this->render('admin/index.html.twig', [
            'nbRestorers' => $nbRestorers,
            'nbOrders' => $nbOrders,
            'nbOrdersFinished' => $nbOrdersFinished,
            'benefits' =>  $benefits
        ]);

        
    }
 

    /**
     * @Route("/Restaurants", name="restorer_index", methods={"GET"})
     */
    public function restorers(RestorerRepository $restorerRepository): Response
    {
        return $this->render('restorer/index.html.twig', [
            'restorers' => $restorerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Utilisateurs", name="admin_client_index", methods={"GET"})
     */
    public function users(UserClientRepository $userClientRepository): Response
    {
        return $this->render('user_client/index.html.twig', [
            'user_clients' => $userClientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Utilisateur/Edition/{id}", name="admin_client_edit", methods={"GET","POST"})
     */
    public function editClient(Request $request, UserClient $userClient): Response
    {
        $form = $this->createForm(UserClientType::class, $userClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_client_index');
        }

        return $this->render('user_client/edit.html.twig', [
            'user_client' => $userClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Restaurants/Edition/{id}", name="admin_edit_restorer", methods={"GET","POST"})
     */
    public function editRestorer(Request $request, Restorer $restorer): Response
    {
        $form = $this->createForm(RestorerType::class, $restorer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restorer_index');
        }

        return $this->render('restorer/edit.html.twig', [
            'restorer' => $restorer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Supprimer/{id}", name="admin_delete_restorer", methods={"DELETE"})
     */
    public function deleteRestorer(Request $request, Restorer $restorer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restorer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restorer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restorer_index');
    }

    /**
     * @Route("/delete/{id}", name="admin_client_delete", methods={"DELETE"})
     */
    public function deleteClient(Request $request, UserClient $userClient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userClient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_client_index');
    }

    /**
     * @Route("/show/{id}", name="admin_client_show", methods={"GET"})
     */
    public function showClient(UserClient $userClient): Response
    {
        return $this->render('user_client/show.html.twig', [
            'user_client' => $userClient,
        ]);
    }

    /**
     * @Route("/Restaurant/Infos/{id}", name="admin_restorer_show", methods={"GET"})
     */
    public function showRestorer(Restorer $restorer): Response
    {
        $user = $this->getUser();
        $id = $user->getId();

        return $this->render('restorer/show.html.twig', [
            'restorer' => $restorer,
        ]);
    }
}
