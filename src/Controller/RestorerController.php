<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Restorer;
use App\Form\RestorerType;
use App\Repository\OrderRepository;
use App\Repository\RestorerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/MonRestaurant")
 */
class RestorerController extends AbstractController
{
    /**
     * @Route("/Historique", name="restorer_history", methods={"GET"})
     */
    public function history(RestorerRepository $restorerRepository): Response
    {   
        $user = $this->getUser();
        $id = $user->getId();
        $restorer = $restorerRepository->find($id);

        $orders = $restorer->getOrders();
        return $this->render('restorer/index.html.twig', [
            'orders' => $orders,
        ]);
    }

        /**
     * @Route("/Historique/Commande", name="restorer_order_history", methods={"GET"})
     */
    public function historyOrder(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findOrderByRestaurant($user);

        return $this->render('restorer/historyOrder.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/DetailsCommande/{id}", name="order_details_restorer", methods={"GET"})
     */
    public function orderDetails(Order $order): Response
    {
        $user = $this->getUser();
        $idUser = $user->getId();
        if($order->getRestaurant()->getId() !== $idUser){
            $this->addFlash('danger', 'La commande que vous cherchez n\'existe pas');
            return $this->redirectToRoute('restorer_history');
        }
        
        return $this->render('restorer/detailsOrder.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/UpdateCommande/{id}", name="order_update", methods={"GET"})
     */
    public function updateOrder(Order $order, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $idUser = $user->getId();
        if($order->getRestaurant()->getId() !== $idUser){
            $this->addFlash('danger', 'La commande que vous cherchez n\'existe pas');
            return $this->redirectToRoute('restorer_history');
        }
        $order->updateState();
        $em->flush($order);
        return $this->redirectToRoute('order_details_restorer',["id" => $order->getId()]);
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
     * @Route("Infos", name="restorer_show", methods={"GET"})
     */
    public function show(RestorerRepository $restorerRepository): Response
    {
        $user = $this->security->getUser();
        $id = $user->getId();
        $restorer = $restorerRepository->find($id);

        return $this->render('restorer/show.html.twig', [
            'restorer' => $restorer,
        ]);
    }

    /**
     * @Route("/Modifier", name="restorer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,UserPasswordEncoderInterface $encoder, RestorerRepository $restorerRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $restorer = $restorerRepository->find($id);

        $form = $this->createForm(RestorerType::class, $restorer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile */;
             $file = $form->get('coverFile')->getData();
             if($file){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('upload_dir'),
                    $filename
                );
                $restorer->setImage($filename);
            }

            $hash = $encoder->encodePassword($restorer, $restorer->getPassword());
            $restorer->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('restorer/edit.html.twig', [
            'restorer' => $restorer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Supprimer/{id}", name="restorer_delete", methods={"DELETE"})
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
