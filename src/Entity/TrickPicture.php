<?php

namespace App\Entity;

use App\Repository\TrickPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickPictureRepository::class)]
class TrickPicture
{
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $main = null;

    #[ORM\ManyToOne(inversedBy: 'pictures', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function isMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(?bool $main): static
    {
        $this->main = $main;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
