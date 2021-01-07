<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Entity\Restorer;
use App\Entity\UserClient;
use App\Form\RestorerType;
use App\Form\UserClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
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
   * @Route("/restaurant/{id}", name="restaurant")
   */
  public function plat_id(PlatRepository $platRepository): Response
  {
    return $this->render('article/index.html.twig', [
      'controller_name' => 'IndexController',
      'plats' => $platRepository
    ]);
  }




    /**
     * @Route("/new/restorer", name="restorer_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('restorer_index');
        }

        return $this->render('restorer/new.html.twig', [
            'restorer' => $restorer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/user", name="user_client_new", methods={"GET","POST"})
     */
    public function newUser(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $userClient = new UserClient();
        $form = $this->createForm(UserClientType::class, $userClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userClient);
            $userClient->setRoles(["ROLE_USER"]);
            $hash = $encoder->encodePassword($userClient, $userClient->getPassword());
            $userClient->setPassword($hash);
            $entityManager->flush();

            return $this->redirectToRoute('user_client_index');
        }

        return $this->render('user_client/new.html.twig', [
            'user_client' => $userClient,
            'form' => $form->createView(),
        ]);
    }
}
