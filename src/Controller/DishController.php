<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\DishRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dish")
 */
class DishController extends AbstractController
{

    /**
     * @Route("/new", name="dish_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('dishFile')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
              $this->getParameter('upload_dir'),
              $filename
            );
            $dish->setImage($filename);
            $dish->setRestaurant($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('dish/new.html.twig', [
            'dish' => $dish,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dish_show", methods={"GET"})
     */
    public function show(Dish $dish): Response
    {
        

        return $this->render('dish/show.html.twig', [
            'dish' => $dish,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dish_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Dish $dish): Response
    {
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dish_index');
        }

        return $this->render('dish/edit.html.twig', [
            'dish' => $dish,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dish_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Dish $dish): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dish->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dish);
            $entityManager->flush();
        }

        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirectToRoute('dishs_restorer');
    }
}
