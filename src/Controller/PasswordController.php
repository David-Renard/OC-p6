<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\ForgottenPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Security\AccessTokenHandler;
use App\Security\AppCustomAuthenticator;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class PasswordController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager) {
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, SendMail $mail): Response
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

            // let's see if this user is an instance of User::class to send an email
            if (!($user instanceof User)) {
                $this->addFlash('error', "Aucun compte avec cette adresse mail n'existe sur SnowTricks, veuillez réessayer :");
            } else {
                // create a token
                $token  = bin2hex(random_bytes(32));
                $status = "active";
                $type   = "forgot-password";

                // persist this token & flush it in DB
                $accessToken = new Token();
                $accessToken->setValue($token);
                $accessToken->setUserInfo($user);
                $accessToken->setStatus($status);
                $accessToken->setType($type);

                $this->manager->persist($accessToken);
                $this->manager->flush();

                // send a email
                $mail->send(
                    'support@snowtricks.com',
                    $identifier,
                    'Réinitialisation - mot de passe SnowTricks',
                    'reset-password',
                    [
                        'expiration_date' => new \DateTime('+3 days'),
                        'user'            => $user,
                        'token'           => $token,
                    ]
                );

                // redirect the user to homepage if the form is valid and he's an instance of User::class
                $this->addFlash('success' , "Un email vous a été envoyé afin de réinitialiser votre mot de passe.");

                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('password/forgot.html.twig', [
            'forgottenPasswordForm' => $form->createView(),
        ]);

    }


    #[Route('/reset-password/{token}/{identifier}', name: 'reset_password')]
    public function newPassword(
        Request $request,
        UserRepository $userRepository,
        TokenRepository $tokenRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        AppCustomAuthenticator $customAuthenticator,
        UserAuthenticatorInterface $userAuthenticator,
        AccessTokenHandler $accessToken,
        string $identifier,
        string $token,
    ): Response
    {
        $user = $userRepository->findOneByIdentifier($identifier);
        $type = "forgot-password";
        // check Token validity
        if ($accessToken->isValid($token, $user, $type)) {
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

                $this->manager->flush($user);
                $this->addFlash('success', "Votre mot de passe a été modifié avec succès.");

                $updatedToken = $tokenRepository->findOneByValue($token);
                $updatedToken->setStatus("disabled");
                $this->manager->flush($updatedToken);
                // return $this->redirectToRoute('homepage');
                return $userAuthenticator->authenticateUser($user, $customAuthenticator, $request);
            }
            return $this->render('password/reset.html.twig', [
               'resetPasswordForm' => $form->createView(),
               'user'              => $user,
            ]);
        }
        // if we are here token is not valid
        $this->addFlash('error', "Le token renseigné est invalide. Vous pouvez refaire une demande de changement de mot de passe.");
        return $this->redirectToRoute("app_forgot_password");
    }
}
