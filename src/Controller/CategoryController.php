<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
