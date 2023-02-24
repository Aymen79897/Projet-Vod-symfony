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
{#[Route('/user_list')]
      public function userList(UserRepository $userRepository)
{
    $users = $userRepository->findAll();

    return $this->render('list.html.twig', [
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
    public function banUse(User $user, EntityManagerInterface $em)
    {
        if($this->isGranted('ROLE_ADMIN')) {
            $user->setIsBanned(true);
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('user_list');
    }
    #[Route('/login',name: 'app_user_login')]
    public function login()
    {
        $form = $this->createForm(LoginType::class);

        if ($form->isSubmitted() && $form->isValid()){
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
}
