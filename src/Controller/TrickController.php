<?php

namespace App\Controller;

use App\Entity\TrickComment;
use App\Form\CommentFormType;
use App\Repository\TrickCommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TrickController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    #[Route('/{page}',name: 'homepage', requirements: ['page' => '\d+'])]
    public function show(TrickRepository $trickRepository, ?int $page = 1): Response
    {
        $tricks = $trickRepository->findBy(array(), ['createdAt' => 'DESC'], 15 * $page);
        $count  = count($trickRepository->findAll());

        return $this->render('trick/index.html.twig', ['tricks' => $tricks, 'page' => $page, 'count' => $count]);
    }

    #[Route('/show/{slug}/{page}', name: 'show_trick', requirements: ['page' => '\d+'])]
    public function showOne(Request $request, TrickCommentRepository $trickCommentRepository, TrickRepository $trickRepository, string $slug, ?int $page = 1, ?int $limit = 5): Response
    {
        $trick = $trickRepository->findOneBy([
           'slug' => $slug,
        ]);
        $count = count($trick->getComments());

        $comments = $trickCommentRepository->findCommentsPaginated($slug, $page);

        $comment = new TrickComment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($this->getUser() && $form->isSubmitted() && $form->isValid()) {
            // create a new comment instance and bind it to the user and to the trick
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);

            $this->manager->persist($comment);
            $this->manager->flush($comment);

            $this->addFlash('success', "Votre commentaire a bien été posté!");

            return $this->redirectToRoute('show_trick', ['slug' => $slug]);
        }

        return $this->render('trick/show.html.twig', [
            'trick'       => $trick,
            'commentForm' => $form->createView(),
            'comments'    => $comments,
            'count'       => $count,
            ]);
    }

    #[Route('/new', name: 'new_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')] // same thing than $this->denyAccessUnlessGranted('ROLE_USER');
    public function new(): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_USER');

        return new Response('bienvenue sur la page de création de tricks');
    }
}
