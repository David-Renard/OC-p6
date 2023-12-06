<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPicture;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{


    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly FileUploader $fileUploader,
    ){}

    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    #[Route('/edit-profile', name: 'edit_profile')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUsername = $form->get('username')->getData();
            if ($newUsername != '') {
                $user->setUsername($newUsername);
                $this->manager->flush($user);
                $this->addFlash('success', "Votre login a bien été modifié.");
            }

            $avatarFile = $form->get('userPicture')->getData();
            if ($avatarFile != null) {
                $avatarFile = $this->fileUploader->upload($avatarFile,'/avatar');

                $avatar = new UserPicture();
                $avatar->setUrl($avatarFile);
                $avatar->setName('avatar'.$this->getUser()->getUsername());
                $avatar->setUserInfo($this->getUser());

                $this->manager->persist($avatar);
                $this->manager->flush();

                $this->addFlash('success', "Votre avatar a bien été modifié.");
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user_info/edit-profile.html.twig', [
           'form' => $form->createView(),
        ]);
    }
}