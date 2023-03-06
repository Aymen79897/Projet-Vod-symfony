<?php
namespace App\Controller;
use App\Entity\Video;
use App\Repository\SerieRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
public function index(VideoRepository $videoRepository,SerieRepository $serieRepository) : Response
    {
        $series = $serieRepository->findBy([], ['rating' => 'DESC'], 7);
        $videos = $videoRepository->findBy([], ['rating' => 'DESC'], 7);
        $username = null;
        $user = $this->getUser();
        if ($user) {
            $username = $user->getUsername();
            if($user->isBanned()){
                return $this->redirectToRoute('banned');
            }
        }
        return $this->render('home/base.html.twig', ['username' => $username,'videos' => $videos,'series' => $series]);
    }
}
