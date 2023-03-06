<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Entity\Video;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/series', name: 'serie_index')]
    public function index(SerieRepository $serieRepository): Response
    {
        $username = null;
        $user = $this->getUser();
        if ($user) {
            $username = $user->getUsername();
        }
        $series = $serieRepository->findAll();

        return $this->render('home/serieListe.html.twig', [
            'series' => $series, 'username'=> $username
        ]);
    }
    #[Route('admin/serie/create', name: 'serie_create', methods: ["GET,POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie created successfully.');

            return $this->redirectToRoute('serie_index', ['id' => $serie->getId()]);
        }

        return $this->render('admin/addseries.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/serie/{id}', name: 'serie_show', methods: ["GET"])]
    public function show(Serie $serie): Response
    {
        $username = null;
        $user = $this->getUser();
        if ($user) {
            $username = $user->getUsername();
        }
        return $this->render('serie/show.html.twig', [
            'serie' => $serie,'username'=> $username
        ]);
    }


    #[Route('/serie/{id}/edit', name: 'serie_edit')]
    public function edit(Request $request, Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Serie updated successfully.');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('admin/serieEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/serie/{id}/delete', name: 'serie_delete')]
    public function delete(Request $request, Serie $serie, EntityManagerInterface $entityManager): Response
    {

            $entityManager->remove($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie deleted successfully.');


        return $this->redirectToRoute('serie_index');
    }


    #[Route('admin/serie/{serieId}/add-video/{videoId}', name: 'serie_add_video', methods: ["POST"])]
    public function addVideoToSerie(int $serieId, int $videoId, EntityManagerInterface $entityManager): Response
    {
        $serie = $entityManager->getRepository(Serie::class)->find($serieId);
        $video = $entityManager->getRepository(Video::class)->find($videoId);

        if (!$serie || !$video) {
            throw $this->createNotFoundException('Serie or Video not found.');
        }

        $serie->addVideo($video);
        $entityManager->flush();

        $this->addFlash('success', 'Video added to series successfully.');

        return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
    }
    #[Route('/admin/serie', name: 'series_admin')]
      public function serieAdminIndex(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->render('admin/crudSeries.html.twig', [
            'series' => $series
        ]);
    }
    #[Route('/admin/serie/{id}/videos', name: 'serie_show_videos')]
    public function adminVideoInSerie(int $id,SerieRepository $serieRepository,VideoRepository $videoRepository): Response
    {
        $serie = $serieRepository->find($id);
        $videos =  $videoRepository->findAll();
        return $this->render('admin/Videos_in_Serie.html.twig', [
            'videos' => $videos,
            'serie' => $serie
        ]);
    }

}
