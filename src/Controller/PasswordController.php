<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\ForgottenPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\SendMail;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{


    public function __construct(private readonly EntityManagerInterface $manager)
    {

    }


    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, SendMail $mail): Response
    {
        // If a user exists he's here redirected to homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ForgottenPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $identifier = $form->get('email')->getData();
            $user = $userRepository->findOneByIdentifier($identifier);

            // Let's see if this user is an instance of User::class to send an email
            if ($user instanceof User === false) {
                $this->addFlash('error', "Aucun compte avec cette adresse mail n'existe sur SnowTricks, veuillez réessayer :");
                return $this->redirectToRoute('app_forgot_password');
            } else if ($user->isVerified() === false) {
                $this->addFlash('error', "Vous devez valider votre compte avant de pouvoir réaliser cette action.");
                return $this->redirectToRoute('homepage');
            }

            if ($user->isVerified() === true) {
                // Create a token
                $token  = bin2hex(random_bytes(32));
                $status = "waiting";
                $type   = "forgot-password";

                // Persist this token & flush it in DB
                $accessToken = new Token();
                $accessToken->setValue($token);
                $accessToken->setUserInfo($user);
                $accessToken->setStatus($status);
                $accessToken->setType($type);

                $this->manager->persist($accessToken);
                $this->manager->flush();

                // Send a email!
                $mail->send(
                    'support@snowtricks.com',
                    $identifier,
                    'Réinitialisation - mot de passe SnowTricks',
                    'reset-password',
                    [
                                'user'            => $user,
                                'token'           => $token,
                                'expiration_date' => new \DateTime('+3 days'),
                    ]
                );

                // Redirect the user to homepage if the form is valid and he's an instance of User::class
                $this->addFlash('success' , "Un email vous a été envoyé afin de réinitialiser votre mot de passe.");

                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('password/forgot.html.twig', [
            'forgottenPasswordForm' => $form->createView(),
        ]);

    }

    #[Route('/reset-password/{tokenString}', name: 'reset_password')]
    public function newPassword(
        Request $request,
        UserRepository $userRepository,
        TokenRepository $tokenRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        TokenService $tokenGroup,
        string $tokenString,
    ): Response {
        if ($tokenGroup->isAwaiting($tokenString) === false) {
            $this->addFlash('error', "Le token a déjà été utilisé, vous pouvez refaire une demande de changement de mot de passe.");

            return $this->redirectToRoute("app_forgot_password");
        }

        $token = $tokenRepository->findOneByValue($tokenString);

        $type  = "forgot-password";
        $user  = $token->getUserInfo();

        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the new plainPassword and encode it before persist in DB if token is valid
            $identifier = $form->get('email')->getData();
            $user       = $userRepository->findOneByIdentifier($identifier);

            if ($tokenGroup->isValid($tokenString, $user, $type) == true) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData(),
                    )
                );

                $this->manager->flush($user);
                $this->addFlash('success', "Votre mot de passe a été modifié avec succès.");

                $updatedToken = $token->setStatus("confirmed");
                $this->manager->flush($updatedToken);

                return $this->redirectToRoute('homepage');
            }
            // If we are here, token is not valid
            $this->addFlash('error', "Le token renseigné est invalide. Vous pouvez refaire une demande de changement de mot de passe.");

            return $this->redirectToRoute("app_forgot_password");
        }
        return $this->render('password/reset.html.twig',
            [
                'resetPasswordForm' => $form->createView(),
                'user'              => $user,
            ]
        );
    }
}
