<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Repository\TrickCommentRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{


    public function __construct(private readonly EntityManagerInterface $manager, private readonly SluggerInterface $slugger)
    {
    }


    #[Route('/{page}',name: 'homepage', requirements: ['page' => '\d+'])]
    public function show(TrickRepository $trickRepository, ?int $page=1): Response
    {
        $tricks = $trickRepository->findBy(array(), ['createdAt' => 'DESC'], 15 * $page);
        $count  = count($trickRepository->findAll());

        return $this->render('trick/index.html.twig',
            [
                'tricks' => $tricks,
                'page' => $page,
                'count' => $count
            ]);
    }


    #[Route('/show/{slug}/{page}', name: 'show_trick', requirements: ['page' => '\d+'])]
    public function showOne(Request $request, TrickCommentRepository $commentRepository, TrickRepository $trickRepository, string $slug, ?int $page = 1): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug,]);
        if ($trick == []) {
            $this->addFlash('error', "Cette figure n'existe pas.");
            return $this->redirectToRoute('homepage');
        }
        $count = count($trick->getComments());

        $comments = $commentRepository->findCommentsPaginated($slug, $page);

        $comment = new TrickComment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($this->getUser() && $form->isSubmitted() && $form->isValid()) {
            // Create a new comment instance and bind it to the user and to the trick
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);

            $this->manager->persist($comment);
            $this->manager->flush($comment);

            $this->addFlash('success', "Votre commentaire a bien été posté!");

            return $this->redirectToRoute('show_trick',
                [
                    'slug' => $slug
                ]);
        }

        return $this->render('trick/show.html.twig',
            [
                'trick'       => $trick,
                'count'       => $count,
                'comments'    => $comments,
                'commentForm' => $form->createView(),
            ]);
    }


    #[Route('/new', name: 'new_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newName = ucwords($form->get('name')->getData());
            $trick->setName($newName);
            $trick->setSlug($this->slugger->slug($newName));

            // Set author
            $trick->setAuthor($this->getUser());

            // Set pictures
            $fileUploader->uploadAndPersist('attachment', $form, $this->manager, $trick);

            $this->manager->persist($trick);
            $this->manager->flush();

            $this->addFlash('success', $trick->getName()." a bien été créée.");

            return $this->redirectToRoute('show_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/add-trick.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * Edit the trick with this $slug (the medias of this trick are deleted elsewhere in MediaController)
     *
     * @param Request $request
     * @param TrickRepository $trickRepository
     * @param FileUploader $fileUploader
     * @param string $slug
     * @return Response
     */
    #[Route('/edit/{slug}', name: 'edit_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    // Same thing than $this->denyAccessUnlessGranted('ROLE_USER');
    public function edit(Request $request, TrickRepository $trickRepository, FileUploader $fileUploader, string $slug): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug,]);

        if ($trick == []) {
            $this->addFlash('error', "Cette figure n'existe pas.");
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newName = ucwords($form->get('name')->getData());
            $trick->setName($newName);
            $trick->setSlug($this->slugger->slug($newName));
            $trick->setUpdatedAt(new \DateTimeImmutable());

            // Change trick's author if the user checked the author's box
            if ($form->get('author')->getData() == true) {
                $trick->setAuthor($this->getUser());
            }

            // Set pictures
            $fileUploader->uploadAndPersist('attachment', $form, $this->manager, $trick);

            $this->manager->flush();

            $this->addFlash('success', "La figure a bien été modifiée.");
            return $this->redirectToRoute("show_trick", ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/edit-trick.html.twig',
            [
                'editTrickForm'   => $form->createView(),
                'trick'           => $trick,
            ]);
    }


    #[Route('delete/{slug}', name: 'delete_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function delete(TrickRepository $trickRepository, string $slug): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        if ($trick == []) {
            $this->addFlash('error', "Cette figure n'existe pas.");
            return $this->redirectToRoute('homepage');
        }

        $this->manager->remove($trick);
        $this->manager->flush();

        $this->addFlash('success', "La figure a bien été supprimée.");
        return $this->redirectToRoute('homepage');
    }
}
