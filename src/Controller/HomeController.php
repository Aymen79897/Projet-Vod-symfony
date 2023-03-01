<?php
namespace App\Controller;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
public function index(VideoRepository $videoRepository) : Response
    {
        $videos= $videoRepository->findAll();
        $username = null;
        $user = $this->getUser();
        if ($user) {
            $username = $user->getUsername();
        }
        return $this->render('home/base.html.twig', ['username' => $username,'videos' => $videos]);
    }
}
