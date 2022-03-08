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
        // Call Entity Manager
            $em = $this
            ->getDoctrine()
            ->getManager();

        // Call UserRepo
        $userRepo = $em->getRepository(User::class);

        // Call function
        $result = $userRepo->getUserAscending();

        // Return result to View
        return $this->render('user/index.html.twig', [
            'user' => $result
        ]);
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
    #[Route('/user/details/{id}', name: 'user_details')]
    public function detailsAction($id)
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);
        return $this->render('user/details.html.twig', [
            'user' => $user
        ]);
    }
    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function editAction($id, Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $user = $em
              ->getRepository('App:User')
              ->find($id);

        $form = $this->createForm(UserType::class, $user);

        if ($this->saveEditedData($form, $request, $user)) {
            $this->addFlash(
                'notice',
                'User Edited'
            );
            return $this->redirectToRoute('user_ascending');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteAction($id)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $user = $em
              ->getRepository('App:User')
              ->find($id);
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'error',
            'User deleted'
        );

        return $this->redirectToRoute('user_ascending');
    }
    public function saveEditedData($form, $request, $user)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setName($request->request->get('user')['name']);
            $user->setAge($request->request->get('user')['age']);

            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($user);
            $em->flush();

            return true;
        }

        return false;
    }
}
