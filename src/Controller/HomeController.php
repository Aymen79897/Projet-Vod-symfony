<?php
namespace App\Controller;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
public function index() : Response
    {
        return $this->render('home/base.html.twig');
    }

    /*public function index(VideoRepository $videoRepository): Response
    {
        $videos= $videoRepository->findAll();
        return $this->render('video/index.html.twig',
            ['videos' => $videos]);
    } */


}
