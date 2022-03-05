<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    #[Route('/category/all/ascending', name: 'category_ascending')]
    public function categoryAscending()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();

        // Call CustomerRepo
        $categoryRepo = $em->getRepository(Category::class);

        // Call function
        $result = $categoryRepo->getCategoryAscending();

        // Return result to View
        return $this->render('category/index.html.twig', [
            'category' => $result
        ]);
    }
    #[Route('/category/details/{id}', name: 'category_details')]
    public function detailsCategoryAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository('App:Category')
            ->find($id);
        return $this->render('category/details.html.twig', [
            'category' => $category
        ]);
    }
    #[Route('/category/delete/{id}', name: 'category_delete')]
    public function deleteCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('App:Category')->find($id);
        $em->remove($category);
        $em->flush();

        $this->addFlash(
            'error',
            'Category deleted'
        );

        return $this->redirectToRoute('category_ascending');
    }

    #[Route('/category/create', name: 'category_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_ascending',[], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('category/create.html.twig',[
            'category'=>$category,
            'form'=>$form]);
    }

    #[Route('/category/edit/{id}', name: 'category_edit')]
    public function editCategoryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('App:Category')->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        if ($this->saveChanges($form,$request,$category)) {
            $this->addFlash(
                'notice',
                'Product Edited'
            );
            return $this->redirectToRoute('category_ascending');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function saveChanges($form, $request, $category)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName($request->request->get('category')['name']);


            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return true;
        }

        return false;
    }
}
