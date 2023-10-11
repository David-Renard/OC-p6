<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Symfony\Component\Clock\now;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/create-trick', name: 'create_trick')]
    public function createTrick(EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $trick = new Trick();
        $createAt = now();
        $trick->setName('Nose-Roll 180');
        $trick->setSlug('nose-roll-180');
        $trick->setCreatedAt($createAt);
        $trick->setUpdatedAt($createAt);
        $trick->setDescription('Amorce un virage sur les orteils ou les talons, et une fois que tu es sur la carre, soulève le talon de ta planche, en gardant la spatule au sol. Ensuite, fais pivoter la planche pour atterrir en Switch.');

        $errors = $validator->validate($trick);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        $entityManager->persist($trick);
        $entityManager->flush();
        return new Response('Saved new product with id '.$trick->getId());
    }

    #[Route('/show/{slug}', name: 'show')]
    public function show(Trick $trick): Response
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
