<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response($this->render('test/index.html.twig',[
            'number' => 0,
            'random' => 0,
        ]));
    }

    #[Route('/number/{number}', name: "lucky_number")]
    public function luckyNumber(int $number = null): Response
    {
        $random = random_int(0,$number);

        return new Response($this->render('test/index.html.twig',[
            'number' => $number,
            'random' => $random,
        ]));
    }
}
