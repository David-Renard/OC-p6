<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgottenPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class PasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ForgottenPasswordFormType::class);
        $form->handleRequest($request);

        // if a user exists he's here redirected to homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $identifier = $form->get('email')->getData();
            $user = $userRepository->findOneByIdentifier($identifier);

//            dd($user);
            // let's see if this user is an instance of User::class to send an email
            if (!($user instanceof User)) {
                dd(36);
                $this->addFlash('error', "Aucun compte avec cette adresse mail existe sur SnowTricks, veuillez réessayer.");
            } else {
                // send a email
//                dd(40);
//                $email = (new TemplatedEmail())
//                    ->from('support@snowtricks.com')
//                    ->to($identifier)
//                    ->subject('Réinitialisation mot de passe SnowTricks')
//                    ->htmlTemplate('password/reset_password.html.twig')
//                    ->context([
//                        'expiration_date' => new \DateTime('+3 days'),
//                        'username'        => $user->getUsername(),
//                    ]);
//
//                $mailer->send($email);

                // redirect the user to homepage if the form is valid and he's an instance of User::class
                $this->addFlash('success' , "Un email vous a été envoyé afin de réinitialiser votre mot de passe.");

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('password/reset.html.twig', [
            'forgottenPasswordForm' => $form->createView(),
        ]);
    }


        #[Route('/new-password/{identifier}', name: 'new-password')]
//        #[Route('/new-password/{token}/{identifier}', name: 'new-password')]
        public function newPassword(
            Request $request, UserRepository $userRepository,
            UserPasswordHasherInterface $userPasswordHasher,
            EntityManagerInterface $manager,
            string $identifier,
            AppCustomAuthenticator $customAuthenticator,
            UserAuthenticatorInterface $userAuthenticator,
        )
//        public function newPassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager, string $token, string $identifier)
        {
            // have to verify token and duration when it will be in
            // if token is valid {

            $user = $userRepository->findOneByIdentifier($identifier);
            if ($user instanceof User) {
                $form = $this->createForm(ResetPasswordFormType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // get the new plainPassword and encode it before persist in DB
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData(),
                        )
                    );
                    $manager->flush($user);
                    $this->addFlash('success', "Votre mot de passe a été modifié avec succès.");
//                    return $this->redirectToRoute('homepage');
                    return $userAuthenticator->authenticateUser($user, $customAuthenticator, $request);
                }

                return $this->render('password/reset_password.html.twig', [
                   'resetPasswordForm' => $form->createView(),
                ]);
            }
        }
}
