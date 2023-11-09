<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPicture;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // if a user exists he's here redirected to homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // assign default picture to this user
            $userPicture = new UserPicture();
            $userPicture->setUrl("/build/images/default-user-icon.jpg");
            $userPicture->setName("default avatar");
            $user->setUserPicture($userPicture);

            $entityManager->persist($user);
            $entityManager->persist($userPicture);
            $entityManager->flush();

            // generate and send email verification
            $email = (new TemplatedEmail())
                ->from('support@snowtricks.com')
                ->to($user->getEmail())
                ->subject('Validation de ton compte SnowTricks')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'expiration_date' => new \DateTime('+3 days'),
                    'username'        => $user->getUsername(),
                ]);

            $mailer->send($email);

            $this->addFlash('success', "Inscription réussie, mais valide l'email avant de pouvoir ride avec nous!");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
