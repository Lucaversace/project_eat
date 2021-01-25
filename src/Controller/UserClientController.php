<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Order;
use App\Entity\UserClient;
use App\Form\NoteType;
use App\Form\UserClientType;
use App\Form\WalletType;
use App\Repository\LineArticleRepository;
use App\Repository\DishRepository;
use App\Repository\UserClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class UserClientController extends AbstractController
{

    /**
     * @Route("/Historique", name="user_client_history", methods={"GET"})
     */
    public function history(): Response
    {
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('user_client/historyOrder.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/DetailsCommande/{id}", name="order_details_client", methods={"GET"})
     */
    public function orderDetails(Order $order): Response
    {
        $user = $this->getUser();
        $idUser = $user->getId();
        if($order->getUser()->getId() !== $idUser){
            $this->addFlash('danger', 'La commande que vous cherchez n\'existe pas');
            return $this->redirectToRoute('user_client_history');
        }
        
        return $this->render('user_client/detailsOrder.html.twig', [
            'order' => $order,
        ]);
    }

     /**
     * @Route("/MesNotes", name="note_client", methods={"GET", "POST"})
     */
    public function myNotes(Request $request, LineArticleRepository $lineArticleRepository, DishRepository $dishRepository): Response
    {
        $user = $this->getUser();
        
        $lineArticles = $lineArticleRepository->findDishByStatusAndUser($user);

        $formulaires = [];
        $dishs = [];

        $note = new Note();
        
        foreach($lineArticles as $lineArticle){

            $dish = $lineArticle->getDish();
            $dishs[] = $dish;

            $form = $this->createForm(NoteType::class, $note);
            $form->handleRequest($request);
            $form1 = $form->createView();
            $formulaires[] = $form1;
        }
            if ($form->isSubmitted() && $form->isValid()) {
                dd($form->get('dish')->getData());  
                $note->setUserClient($user)
                     ->setDish($form->get('dish')->getData())
                ;

                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('note_client');
            }
        
        return $this->render('user_client/notes.html.twig', [
            'formulaires' => $formulaires,
            'lineArticles' => $lineArticles,
            'dishs' => $dishs
        ]);
    }

    /**
     * @Route("/Informations", name="user_client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,UserPasswordEncoderInterface $encoder,UserClientRepository $userClientRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $userClient = $userClientRepository->find($id);

        $form = $this->createForm(UserClientType::class, $userClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $hash = $encoder->encodePassword($userClient, $userClient->getPassword());
            $userClient->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('user_client/edit.html.twig', [
            'user_client' => $userClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_client_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserClient $userClient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userClient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_client_index');
    }




    /**
     * @Route("/Solde", name="user_client_solde", methods={"GET","POST"})
     */
    public function edit_solde(Request $request, UserClientRepository $userClientRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $userClient = $userClientRepository->find($id);

        $form = $this->createForm(WalletType::class, $userClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('user_client/edit_solde.html.twig', [
            'user_client' => $userClient,
            'form' => $form->createView(),
        ]);
    }
}


