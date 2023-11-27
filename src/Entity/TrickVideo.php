<?php

namespace App\Entity;

use App\Repository\TrickVideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickVideoRepository::class)]
class TrickVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Url(message: "L'url {{ value }} n'est pas une adresse valide.", normalizer: 'trim')]
    #[Assert\Length(
        min: 10,
        minMessage: "L'url doit avoir au moins {{ limit }} caractères.",
        max: 200,
        maxMessage: "L'url doit avoir au plus {{ limit }} caractères.",
    )]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'video', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

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

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
