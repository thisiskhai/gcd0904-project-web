<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;
use App\Form\CartType;
use App\Entity\Products;
use App\Entity\User;
use App\Form\ProductsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\stringContains;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => 'CartController',
        ]);
    }
    #[Route('/cart/all/ascending', name: 'cart_ascending')]
    public function listAscendingCart(): Response
    {   
        // Call Entity Manager
            $em = $this
            ->getDoctrine()
            ->getManager();

        // Call UserRepo
        $cartRepo = $em->getRepository(Cart::class);

        // Call function
        $result = $cartRepo->getCartAscending();

        // Return result to View
        return $this->render('cart/list.html.twig', [
            'cart' => $result
        ]);
    }
    #[Route('/cart/create', name: 'cart_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($cart);
            $entityManager->flush();
            return $this->redirectToRoute('cart_ascending',[], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('cart/create.html.twig',[
            'cart'=>$cart,
            'form'=>$form]);
    }
    #[Route('/cart/detail/{id}', name: 'cart_detail')]
    public function detailAction($id)
    {
        $cart = $this->getDoctrine()
            ->getRepository('App:Cart')
            ->find($id);
        return $this->render('cart/detail.html.twig', [
            'cart' => $cart
        ]);
    }
    #[Route('/cart/delete/{id}', name: 'cart_delete')]
    public function deleteAction($id)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $cart = $em
              ->getRepository('App:Cart')
              ->find($id);
        $em->remove($cart);
        $em->flush();

        $this->addFlash(
            'error',
            'Cart deleted'
        );

        return $this->redirectToRoute('cart_ascending');
    }
    #[Route('/cart/edit/{id}', name: 'cart_edit')]
    public function editAction($id, Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $cart = $em
              ->getRepository('App:Cart')
              ->find($id);

        $form = $this->createForm(CartType::class, $cart);

        if ($this->saveEditedData($form, $request, $cart)) {
            $this->addFlash(
                'notice',
                'Cart Edited'
            );
            return $this->redirectToRoute('cart_ascending');
        }

        return $this->render('cart/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function saveEditedData($form, $request, $cart)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setQuantity($request->request->get('cart')['quantity']);

            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($cart);
            $em->flush();

            return true;
        }

        return false;
    }
}