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


    public function __construct(public string $targetDirectory, public SluggerInterface $slugger)
    {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename     = $this->slugger->slug($originalFilename);
        $filename         = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $filename);
        } catch (FileException $e) {

        }

        return $filename;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function uploadAndPersist(string $inputs, FormInterface $form, EntityManagerInterface $manager, Trick $trick): void
    {
        $array = $form->get($inputs)->getData();

        if ($array != []) {
            foreach ($array as $file) {
                $fileName = $this->upload($file);

                $fileToUpload = new TrickPicture();
                $fileToUpload->setName("Freestyle ".substr($fileName, 0, strripos($fileName, "-")));
                $fileToUpload->setMain(false);
                $fileToUpload->setUrl('/build/images/upload/trick_pictures/'.$fileName);
                $fileToUpload->setTrick($trick);

                $manager->persist($fileToUpload);
                $manager->flush();
            }
        }
    }
}