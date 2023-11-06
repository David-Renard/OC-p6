<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TrickController extends AbstractController
{
    #[Route('/{page}',name: 'homepage', requirements: ['page' => '\d+'])]
    public function show(TrickRepository $trickRepository, ?int $page = 1): Response
    {
        $tricks = $trickRepository->findBy(array(), ['createdAt' => 'DESC'], 15 * $page);

        return $this->render('trick/index.html.twig', ['tricks' => $tricks, 'page' => $page]);
    }

    #[Route('/show/{slug}', name: 'show_trick')]
    public function showOne(TrickRepository $trickRepository, string $slug): Response
    {
        $trick = $trickRepository->findOneBy([
           'slug' => $slug,
        ]);

        return $this->render('trick/show.html.twig', ['trick' => $trick]);
    }

    #[Route('/new', name: 'new_trick')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')] // same thing than $this->denyAccessUnlessGranted('ROLE_USER');
    public function new(): Response
    {
//        $this->denyAccessUnlessGranted('ROLE_USER');

        return new Response('bienvenue sur la page de création de tricks');
    }
}
