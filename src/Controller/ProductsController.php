<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\ProductsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\stringContains;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
    #[Route('/products/all/ascending', name: 'products_ascending')]
    public function productsAscending()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();

        // Call CustomerRepo
        $productRepo = $em->getRepository(Products::class);

        // Call function
        $result = $productRepo->getProductsAscending();

        // Return result to View
        return $this->render('products/index.html.twig', [
            'products' => $result
        ]);
    }
    #[Route('/products/details/{id}', name: 'products_details')]
    public function detailsAction($id)
    {
        $products = $this->getDoctrine()
            ->getRepository('App:Products')
            ->find($id);
        return $this->render('products/details.html.twig', [
            'products' => $products
        ]);
    }
    #[Route('/products/delete/{id}', name: 'products_delete')]
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('App:Products')->find($id);
        $em->remove($products);
        $em->flush();

        $this->addFlash(
            'error',
            'Product deleted'
        );

        return $this->redirectToRoute('products_ascending');
    }
    #[Route('/products/create', name: 'products_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = new Products();
        $form = $this->createForm(ProductsType::class, $products);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($products);
            $entityManager->flush();
            return $this->redirectToRoute('products_ascending',[], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('products/create.html.twig',[
            'products'=>$products,
            'form'=>$form]);
    }

    #[Route('/products/edit/{id}', name: 'products_edit')]
    public function editAction(Request $request, Products $products, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ProductsType::class, $products);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('products_ascending',[],Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('products/edit.html.twig', [
            'products'=>$products,
            'form' => $form
        ]);
    }
    #[Route('/products/edit/{id}', name: 'products_edit')]
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('App:Products')->find($id);

        $form = $this->createForm(ProductsType::class, $products);

        if ($this->saveChanges($form,$request,$products)) {
            $this->addFlash(
                'notice',
                'Product Edited'
            );
            return $this->redirectToRoute('products_ascending');
        }

        return $this->render('products/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function saveChanges($form, $request, $products)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products->setName($request->request->get('products')['name']);
            $products->setPrice($request->request->get('products')['price']);
            $products->setDescription($request->request->get('products')['description']);


            $em = $this->getDoctrine()->getManager();
            $em->persist($products);
            $em->flush();

            return true;
        }

        return false;
    }

}
