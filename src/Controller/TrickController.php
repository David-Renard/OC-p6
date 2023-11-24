<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Entity\TrickPicture;
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
    public function show(TrickRepository $trickRepository, ?int $page = 1): Response
    {
        $tricks = $trickRepository->findBy(array(), ['createdAt' => 'DESC'], 15 * $page);
        $count  = count($trickRepository->findAll());

        return $this->render('trick/index.html.twig', ['tricks' => $tricks, 'page' => $page, 'count' => $count]);
    }

    #[Route('/show/{slug}/{page}', name: 'show_trick', requirements: ['page' => '\d+'])]
    public function showOne(Request $request, TrickCommentRepository $trickCommentRepository, TrickRepository $trickRepository, string $slug, ?int $page = 1): Response
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
            // Create a new comment instance and bind it to the user and to the trick
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
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function new(Request $request, FileUploader $fileUploader, TrickRepository $trickRepository): Response
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
            $pictures = $form->get('attachment')->getData();
            if ($pictures) {
                foreach ($pictures as $pictureFile) {
                    $pictureFilename = $fileUploader->upload($pictureFile);

                    $picture = new TrickPicture();
                    $picture->setName("Freestyle ".substr($pictureFilename, 0, strripos($pictureFilename, "-")));
                    $picture->setMain(false);
                    $picture->setUrl('/build/images/upload/trick_pictures/'.$pictureFilename);
                    $picture->setTrick($trick);

                    $this->manager->persist($picture);
                    $this->manager->flush();
                }
            }

            $this->manager->persist($trick);
            $this->manager->flush();

            $this->addFlash('success', $trick->getName()." a bien été créée.");

            return $this->redirectToRoute('show_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/add-trick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')] // Same thing than $this->denyAccessUnlessGranted('ROLE_USER');
    public function edit(Request $request, TrickRepository $trickRepository, string $slug): Response
    {
        $trick = $trickRepository->findOneBy([
            'slug' => $slug,
        ]);

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newName = ucwords($form->get('name')->getData());
            $trick->setName($newName);
            $trick->setSlug($this->slugger->slug($newName));
            $trick->setUpdatedAt(new \DateTimeImmutable());

            // Change trick's author if the user checked the author's box
            if ($form->get('author')->getData()) {
                $trick->setAuthor($this->getUser());
            }

            $this->manager->flush();

            $this->addFlash('success', "La figure a bien été modifiée.");
            return $this->redirectToRoute("show_trick", ['slug' => $trick->getSlug()]);
        }

        return $this->render(
            'trick/edit-trick.html.twig', [
            'editTrickForm'   => $form->createView(),
            'trick'           => $trick,
        ]);
    }

    #[Route('delete/{slug}', name: 'delete_trick')]
    public function delete(TrickRepository $trickRepository, string $slug): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $this->manager->remove($trick);
        $this->manager->flush();

        $this->addFlash('success', "La figure a bien été supprimée.");
        return $this->redirectToRoute('homepage');
    }
}
