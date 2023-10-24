<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('/', 'homepage')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $tricks = $entityManager->getRepository(Trick::class)->findBy(array(), ['createdAt' => 'DESC'], 15);

        return $this->render('trick/index.html.twig', ['tricks' => $tricks]);
    }

    #[Route('/show_more/{number}', name: 'show_more', requirements: ['number' => '\d+'])]
    public function showMore(EntityManagerInterface $entityManager, int $number = 0): Response
    {
        $tricks = $entityManager->getRepository(Trick::class)->findBy(array(), ['createdAt' => 'DESC'], $number + 15);

        return $this->render('trick/index.html.twig', ['tricks' => $tricks, 'number' => $number]);
    }

    #[Route('show/{slug}', name: 'see_trick')]
    public function showOne(EntityManagerInterface $entityManager, string $slug): Response
    {
        $trick = $entityManager->getRepository(Trick::class)->findOneBy([
           'slug' => $slug,
        ]);

        return $this->render('trick/show.html.twig', ['trick' => $trick]);
    }
}
