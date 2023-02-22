<?php
namespace App\Controller;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Entity\User;
use App\Entity\Video;
use App\Form\CommentCreateType;
use App\Form\UploadVideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class VideoController extends AbstractController
{
    #[Route('/video/upload',name: 'video_upload')]
    public function upload(Request $request,EntityManagerInterface $entityManager): Response
    {
        $video = new Video();
        $form = $this->createForm(UploadVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            $video->setUser($this->getUser());

            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('video_detail', ['id' => $video->getId()]);
        }

        return $this->render('video/upload.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/video/{id}',name:"video_detail",methods: ["GET","POST"])]
    public function show(Video $video, Request $request,EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $commentform = $this->createForm(CommentCreateType::class);
        $commentform->handleRequest($request);

        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comment = $commentform->getData();
            $comment->setVideo($video);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('video_detail', ['id' => $video->getId()]);
        }

        return $this->render('video/show.html.twig', ['video' => $video, 'form' => $commentform->createView()]);
    }
    #[Route('/video/edit/{id}',name: 'video_edit')]
    public function edit(Video $video, Request $request,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('video_detail', ['id' => $video->getId()]);
        }

        return $this->render('video/edit.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/video/play/{id}',name: 'video_play')]
    public function play(Video $video): Response
    {
        return $this->render('video/play.html.twig', ['video' => $video]);
    }
 #[Route('/video/delete/{id}',name: 'video_delete')]
    public function delete(Video $video,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($video);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
#[Route('/admin/video/{id}/comment/{commentId}/delete',name: 'video_comment_delete', methods: ["POST","GET"])]
public function deleteComment(Comment $comment,EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($comment);
    $entityManager->flush();

    $this->addFlash('success', 'The comment has been deleted.');

    return $this->redirectToRoute('video_show', ['id' => $comment->getVideo()->getId()]);
}
    #[Route('/video/{id}/favorite', name: 'video_favorite', methods: ['POST'])]
    public function toggleFavorite(Video $video, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'You must be logged in to favorite a video.'], 401);
        }

        $favorite = $entityManager->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            'video' => $video
        ]);

        if (!$favorite) {
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setVideo($video);

            $entityManager->persist($favorite);
            $entityManager->flush();
        } else {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->json(['success' => true]);
    }

}
