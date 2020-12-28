<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{episodeSlug}/new", name="new", methods={"GET","POST"})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeSlug": "slug"}} )
     * @IsGranted("ROLE_CONTRIBUTOR")
     */

    public function new(Request $request, Episode $episode, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($user);
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }


        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form ->createView(),
        ]);

    }

    /**
     * @Route("/{comment_id}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter ("comment", class="App\Entity\Comment", options={"mapping":{"comment_id":"id"}} )
     * @IsGranted ("ROLE_CONTRIBUTOR")
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_show');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/owner/delete", name="owner_delete", methods={"DELETE"})
     * @IsGranted ("ROLE_CONTRIBUTOR")
     */

    public function deleteOwner(Request $request, Comment $comment)
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('episode_index');

    }
}
