<?php

namespace App\Entity;

use App\Repository\TrickVideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickVideoRepository::class)]
class TrickVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2087)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $trickId = null;

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

    public function getTrickId(): ?int
    {
        return $this->trickId;
    }

    public function setTrickId(int $trickId): static
    {
        $this->trickId = $trickId;

        return $this;
    }
}
