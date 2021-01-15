<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Form\UserClientType;
use App\Repository\UserClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class UserClientController extends AbstractController
{
    /**
     * @Route("/users", name="user_client_index", methods={"GET"})
     */
    public function index(UserClientRepository $userClientRepository): Response
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

    /**
     * @Route("/Informations/{id}", name="user_client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserClient $userClient): Response
    {
        $form = $this->createForm(UserClientType::class, $userClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
}
