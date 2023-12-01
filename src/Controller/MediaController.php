<?php

namespace App\Controller;

use App\Repository\TrickPictureRepository;
use App\Repository\TrickRepository;
use App\Repository\TrickVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/edit/{slug}', name: 'media_', requirements: ['id' => '\d+'])]
#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
class MediaController extends AbstractController
{


    public function __construct(private readonly EntityManagerInterface $manager)
    {

    }

    #[Route('/main/{id}', name: 'edit_main_picture', requirements: ['id' => '\d+'])]
    public function declareMainPicture(TrickRepository $trickRepository, TrickPictureRepository $pictureRepository, string $slug, int $id): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $picture = $pictureRepository->find($id);

        $trick->setMainPicture($picture);
        $this->manager->persist($trick);
        $this->manager->flush();

        $this->addFlash('success', "L'image principale de cette figure a bien été modifiée.");
        return $this->redirectToRoute('edit_trick', [
                'slug' => $slug,
            ]
        );
    }

    #[Route('/delete-pic/{id}', name: 'delete_picture', requirements: ['id' => '\d+'])]
    public function deletePicture(TrickPictureRepository $pictureRepository, string $slug, int $id): Response
    {
        $picture = $pictureRepository->find($id);
        $picture->getTrick()->removePicture($picture);

        $this->manager->remove($picture);
        $this->manager->flush();

        $this->addFlash('success', "Cette image a bien été supprimée.");
        return $this->redirectToRoute('edit_trick', [
                'slug' => $slug,
            ]
        );
    }

    #[Route('/delete-vid/{id}', name: 'delete_video', requirements: ['id' => '\d+'])]
    public function deleteVideo(TrickVideoRepository $videoRepository, string $slug, int $id): Response
    {
        $video = $videoRepository->find($id);
        $video->getTrick()->removeVideo($video);

        $this->manager->remove($video);
        $this->manager->flush();

        $this->addFlash('success', "Cette vidéo a bien été supprimée.");
        return $this->redirectToRoute('edit_trick', [
                'slug' => $slug,
            ]
        );
    }
}
