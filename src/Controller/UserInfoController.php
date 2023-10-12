<?php

namespace App\Controller;

use App\Entity\UserInfo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserInfoController extends AbstractController
{
    #[Route('/info', name: 'app_user_info')]
    public function index(): Response
    {
        return $this->render('user_info/index.html.twig', [
            'controller_name' => 'UserInfoController',
        ]);
    }

    #[Route('/create', name: 'create-user')]
    public function createUser(EntityManagerInterface $entityManager): Response
    {
        $user = new UserInfo();
        $user->setName('Lacroix');
        $user->setFirstname('Charles');

        $pass = "chla";
        $passToHash = password_hash($pass, PASSWORD_BCRYPT);
        $user->setPassword($passToHash);
        $user->setEmail("cla@yahoo.fr");
        $user->setLogin("chla");

        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Saved new user with id '.$user->getId().$user->getPassword());
    }
}
