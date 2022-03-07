<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/all/ascending', name: 'user_ascending')]
    public function userAscending()
    {
        // Call Entity Manager
        $em = $this
            ->getDoctrine()
            ->getManager();

        // Call CustomerRepo
        $userRepo = $em->getRepository(User::class);

        // Call function
        $result = $userRepo->getUserAscending();

        // Return result to View
        return $this->render('user/index.html.twig', [
            'user' => $result
        ]);
    }
}
