<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/serie', name: 'serie_index')]
    public function index(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->render('serie/index.html.twig', [
            'series' => $series,
        ]);
    }
    #[Route('/serie/create', name: 'serie_create', methods: ["GET,POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie created successfully.');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/serie/{id}', name: 'serie_show', methods: ["GET"])]
    public function show(Serie $serie): Response
    {
        return $this->render('serie/show.html.twig', [
            'serie' => $serie,
        ]);
    }


    #[Route('/serie/{id}/edit', name: 'serie_edit', methods: ["GET,POST"])]
    public function edit(Request $request, Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Serie updated successfully.');

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/serie/{id}/delete', name: 'serie_create', methods: ["GET,POST"])]
    public function delete(Request $request, Serie $serie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie deleted successfully.');
        }

        return $this->redirectToRoute('serie_index');
    }

}
