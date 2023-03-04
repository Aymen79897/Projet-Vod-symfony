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
use Symfony\Component\Routing\Annotation\Route;
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(){

         return $this->render('admin/adminHome.html.twig');
    }
    #[Route('/admin/add', name: 'admin_add')]
    public function makeUserAdmin( UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();


        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $entityManager->flush();



        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('success', 'User has been made an admin.');
        } else {
            $this->addFlash('error', 'Failed to make user an admin.');
        }

        return $this->redirectToRoute('admin_home');
    }
}