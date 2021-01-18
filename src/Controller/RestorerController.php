<?php

namespace App\Controller;

use App\Entity\Restorer;
use App\Form\RestorerType;
use App\Repository\DishRepository;
use App\Repository\RestorerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restorer")
 */
class RestorerController extends AbstractController
{
    /**
     * @Route("/", name="restorer_index", methods={"GET"})
     */
    public function index(RestorerRepository $restorerRepository): Response
    {
        return $this->render('restorer/index.html.twig', [
            'restorers' => $restorerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Plats/{id}", name="dishs_restorer", methods={"GET"})
     */
    public function plat(RestorerRepository $restorerRepository,$id): Response
    {
        $restorer = $restorerRepository->find($id);
        $dishes = $restorer->getDishs();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        
        ]);
    }


    /**
     * @Route("/{id}", name="restorer_show", methods={"GET"})
     */
    public function show(Restorer $restorer): Response
    {
        return $this->render('restorer/show.html.twig', [
            'restorer' => $restorer,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="restorer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Restorer $restorer): Response
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
     * @Route("/{id}", name="restorer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Restorer $restorer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restorer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restorer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restorer_index');
    }
}
