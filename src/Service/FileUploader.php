<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\TrickPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{


    public function __construct(public string $uploadDirectory, public SluggerInterface $slugger)
    {
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }

    public function upload(UploadedFile $file, String $folder = 'trick_pictures'): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename     = substr($this->slugger->slug($originalFilename),0,5);
        $filename         = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getUploadDirectory().$folder, $filename);
        } catch (FileException $e) {

        }

        return $filename;
    }

    public function uploadAndPersist(string $inputs, FormInterface $form, EntityManagerInterface $manager, Trick $trick, string $folder = 'trick_pictures'): void
    {
        $array = $form->get($inputs)->getData();

        if ($array != []) {
            foreach ($array as $file) {
                $fileName = $this->upload($file, $folder);

                $fileToUpload = new TrickPicture();
                $fileToUpload->setName("Freestyle ".substr($fileName, 0, strripos($fileName, "-")));
                $fileToUpload->setMain(false);
                $fileToUpload->setUrl($fileName);
                $fileToUpload->setTrick($trick);

                $manager->persist($fileToUpload);
            }
            $manager->flush();
        }
    }
}
