<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    #[Route('/user/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_ascending',[], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user/create.html.twig',[
            'user'=>$user,
            'form'=>$form]);
    }
}
