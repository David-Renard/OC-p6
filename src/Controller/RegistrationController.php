<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPicture;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, SendMail $mail, EntityManagerInterface $entityManager): Response
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
            $mail->send(
                'support@snowtricks.com',
                $user->getUserIdentifier(),
                'Validation de compte SnowTricks',
                'confirmation-email',
                [
                    'expiration_date' => new \DateTime('+3 days'),
                    'user'        => $user,
                ]
            );

            $this->addFlash('success', "Inscription réussie, mais valide l'email avant de pouvoir ride avec nous!");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify-user/{identifier}', name: 'verify_user')]
    public function verifyUser(EntityManagerInterface $manager, UserRepository $userRepository, string $identifier): Response
    {
//        if ($token is valid and right in time)
        $user = $userRepository->findOneByIdentifier($identifier);
        // verifying this user is an instance of User::class and still isVerified is false
        if ($user instanceof User && $user->isVerified() === false) {
            $user->setIsVerified(true);

            $manager->flush($user);
            $this->addFlash('success', "Votre compte a bien été vérifié, vous êtes désormais totalement inscrit.");

            return $this->redirectToRoute("homepage");
        } else {
            $this->addFlash('error', "Le compte a déjà été vérifié ou un problème se pose dans la vérification.");

            return $this->redirectToRoute("app_register");
        }
    }
}
