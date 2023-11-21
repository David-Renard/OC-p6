<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use App\Entity\TrickComment;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Repository\TrickCategoryRepository;
use App\Repository\TrickCommentRepository;
use App\Repository\TrickRepository;
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
    public function new(Request $request, TrickRepository $trickRepository, TrickCategoryRepository $categoryRepository): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newName = ucwords($form->get('name')->getData());
            $trick->setName($newName);
            $trick->setSlug($this->slugger->slug($newName));

            // assign or create a new category for this trick
            $newCategoryName = $form->get('newCategoryName')->getData();
            $this->assignCategory($trick, $newCategoryName, $categoryRepository);

            // set author
            $trick->setAuthor($this->getUser());

            $this->manager->persist($trick);
            $this->manager->flush();

            $this->addFlash('success', $trick->getName() . " a bien été créée.");
        }

        return $this->render('trick/add-trick.html.twig', [
            'addTrickForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{slug}', name: 'edit_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')] // same thing than $this->denyAccessUnlessGranted('ROLE_USER');
    public function edit(Request $request, TrickRepository $trickRepository, TrickCategoryRepository $categoryRepository, string $slug): Response
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


            $newCategoryName = $form->get('newCategoryName')->getData();
            // use private function to handle the category of the trick
            $this->assignCategory($trick, $newCategoryName, $categoryRepository);

            // change trick's author if the user checked the author's box
            if ($form->get('author')->getData()) {
                $trick->setAuthor($this->getUser());
            }

            $this->manager->flush();

            $this->addFlash('success', "La figure a bien été modifiée.");
            return $this->redirectToRoute("show_trick", ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/edit-trick.html.twig', [
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

    // persist the category if the user entered a new one and assign this category to the trick if it exists
    private function assignCategory(Trick $trick, string $newCategoryName, TrickCategoryRepository $categoryRepository): void
    {
        $formalizedNewCategoryName = ucwords($newCategoryName);
        $categoryAsArray           = $categoryRepository->findBy(['name' => $formalizedNewCategoryName]);

        if (empty($categoryAsArray)) {
            $category = new TrickCategory();
            $category->setName($formalizedNewCategoryName);
            $this->manager->persist($category);
            $this->manager->flush();

            $trick->setCategory($category);
        } else {
            $this->addFlash("info", "La catégorieque vous vouliez créer existe déjà.");
            $trick->setCategory($categoryAsArray[0]);
        }
    }
}
