<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Restorer;
use App\Entity\UserClient;
use App\Form\AddressType;
use App\Form\RestorerType;
use App\Form\UserClientType;
use App\Repository\RestorerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/Accueil", name="accueil")
     */
    public function index(RestorerRepository $restorerRepository): Response
    {
        $restorers = $restorerRepository->findAll();
        return $this->render('index/index.html.twig', [
            'restorers' => $restorers,
        ]);
    }
     
    /**
    * @Route("/Restaurant/{id}", name="restaurant")
    */
   public function dish_id($id, RestorerRepository $restorerRepository): Response
   {
       $restorer = $restorerRepository->find($id);
        $dishs = $restorerRepository->find($id)->getDishs();
        return $this->render('index/restorer.html.twig', [
        'restorer' => $restorer,
        'dishs' => $dishs,
        ]);
   }


    /**
     * @Route("/Restaurant/Inscription", name="restorer_new", methods={"GET","POST"})
     */
    public function newRestorer(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $restorer = new Restorer();
        $form = $this->createForm(RestorerType::class, $restorer);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $restorer->setRoles(["ROLE_RESTORER"]);
            $hash = $encoder->encodePassword($restorer, $restorer->getPassword());
            $restorer->setPassword($hash);
            $entityManager->persist($restorer);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('restorer/new.html.twig', [
            'restorer' => $restorer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Inscription", name="user_client_new", methods={"GET","POST"})
     */
    public function newUser(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $userClient = new UserClient();
        $form = $this->createForm(UserClientType::class, $userClient);
        $form->handleRequest($request);
        /*   dd($form); */ 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userClient);
            $userClient->setRoles(["ROLE_USER"]);
            $hash = $encoder->encodePassword($userClient, $userClient->getPassword());
            $userClient->setPassword($hash);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('user_client/new.html.twig', [
            'user_client' => $userClient,
            'form' => $form->createView(),
        ]);
    }
}
