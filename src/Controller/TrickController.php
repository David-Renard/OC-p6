<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Symfony\Component\Clock\now;

class TrickController extends AbstractController
{
    #[Route('/', 'homepage')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $tricks = $entityManager->getRepository(Trick::class)->findAll();

        return $this->render('trick/index.html.twig', ['tricks' => $tricks]);
    }

    #[Route('/create-trick', name: 'create_trick')]
    public function createTrick(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $trick = new Trick();
        $createAt = now();
        $trick->setName('Switch Cork 540 Tail Grab');
        $trick->setSlug('switch-cork-540-tail-grab');
        $trick->setCreatedAt($createAt);
        $trick->setUpdatedAt($createAt);
        $trick->setDescription("Nam et velit eget lacus blandit volutpat in et ante.");

        $errors = $validator->validate($trick);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        $entityManager->persist($trick);
        $entityManager->flush();
        return new Response('Saved new product with id '.$trick->getId());
    }

    #[Route('/show/{slug}', name: 'show')]
    public function showTrick(Trick $trick): Response
    {
        return $this->render('trick/index.html.twig',['trick' => $trick]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, Trick $trick): Response
    {
        $entityManager->remove($trick);
        $entityManager->flush();

        return new Response("objet ".$trick->getId()." bien supprimé");
    }
}
