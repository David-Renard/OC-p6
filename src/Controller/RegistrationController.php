<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Entity\UserPicture;
use App\Form\RegistrationFormType;
use App\Repository\TokenRepository;
use App\Service\SendMail;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, SendMail $mail): Response
    {
        // If a user exists he's here redirected to homepage
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Assign default picture to this user
            $userPicture = new UserPicture();
            $userPicture->setUrl("/build/images/default-user-icon.jpg");
            $userPicture->setName("default avatar");
            $user->setUserPicture($userPicture);

            $this->manager->persist($user);
            $this->manager->persist($userPicture);
            $this->manager->flush();

            // Create a token to link it to the mail
            $token  = bin2hex(random_bytes(32));
            $status = "waiting";
            $type   = "registration";

            // Persist this token & flush it in DB
            $accessToken = new Token();
            $accessToken->setValue($token);
            $accessToken->setUserInfo($user);
            $accessToken->setStatus($status);
            $accessToken->setType($type);

            $this->manager->persist($accessToken);
            $this->manager->flush();

            // Generate and send email verification
            $mail->send(
                'support@snowtricks.com',
                $user->getUserIdentifier(),
                'Validation de compte SnowTricks',
                'confirmation-email',
                [
                    'expiration_date' => new \DateTime('+3 days'),
                    'user'        => $user,
                    'token'       => $token,
                ]
            );

            $this->addFlash('success', "Inscription réussie, mais valide l'email avant de pouvoir ride avec nous!");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify-user/{tokenString}', name: 'verify_user')]
    public function verifyUser(
        TokenRepository $tokenRepository,
        TokenService $tokenService,
        string $tokenString,
    ): Response
    {
        if (!$tokenService->isAwaiting($tokenString)) {
            $this->addFlash('error', "Le token a déjà été utilisé, vous pouvez dès maintenant vous connecter.");

            return $this->redirectToRoute("app_login");
        }

        $token = $tokenRepository->findOneByValue($tokenString);

        $type = "registration";
        $user = $token->getUserInfo();

        // Check Token validity
        if ($tokenService->isValid($tokenString, $user, $type)) {
            // Verifying this user is an instance of User::class and still isVerified is false
            if ($user instanceof User && $user->isVerified() === false) {
                $user->setIsVerified(true);
                $this->manager->flush($user);

                $updatedToken = $token->setStatus("confirmed");
                $this->manager->flush($updatedToken);

                $this->addFlash('success', "Votre compte a bien été vérifié, vous êtes désormais totalement inscrit.");

                return $this->redirectToRoute("homepage");
            } else {
                $this->addFlash('error', "Le compte a déjà été vérifié ou un problème se pose dans la vérification.");

                return $this->redirectToRoute("app_register");
            }
        }
        // If token isn't valid, redirect to registration page
        $this->addFlash('error', "Un problème est survenu dans la vérification du token.");

        return $this->redirectToRoute("app_register");
    }
}
