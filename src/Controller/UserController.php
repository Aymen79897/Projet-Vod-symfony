<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\UserCreateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{#[Route('/users', name: 'user_list')]
      public function userList(UserRepository $userRepository): Response
{
    $users = $userRepository->findAll();

    return $this->render('admin/crudUser.html.twig', [
        'users' => $users,
    ]);
}
#[Route('/create')]
      public function CreateUser(Request $request ,EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
{
    $user = New User();
    $form = $this->createForm(UserCreateType::class);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $user = $form->getData();
        $user->setPassword($hasher->hashPassword($user, $user->getPlainPassword()));

        $entityManager->persist($user);
        $entityManager->flush();
    }
    return $this->render('user/UserCreate.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('admin/user/{id}/ban', name: 'ban')]
    public function banUser(User $user, EntityManagerInterface $em):Response
    {

            $user->setBanned(true);
            $em->persist($user);
            $em->flush();

        return $this->redirectToRoute('user_list');
    }
    #[Route('admin/user/{id}/unban', name: 'unban')]
    public function unbanUser(User $user, EntityManagerInterface $em) :Response
    {

            $user->setBanned(false);
            $em->persist($user);
            $em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/login',name: 'app_user_login')]
    public function login()
    {
        $form = $this->createForm(LoginType::class);

        if ($form->isSubmitted() && $form->isValid()){
            $user =  $this->getUser();
            return $this->redirectToRoute('home');
        }
        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/logout',name: 'app_user_logout')]
    public function logout()
    {


    }
    #[Route('/banned',name: 'banned')]
    public function banished(): Response
    {
    return $this->render('user/banned.html.twig');
    }
}
