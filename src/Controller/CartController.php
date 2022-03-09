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

}