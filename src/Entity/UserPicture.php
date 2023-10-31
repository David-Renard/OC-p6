<?php

namespace App\Entity;

use App\Repository\UserPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPictureRepository::class)]
class UserPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'userPicture', cascade: ['persist', 'remove'])]
    private ?User $userInfo = null;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUserInfo(): ?User
    {
        return $this->userInfo;
    }

    public function setUserInfo(?User $userInfo): static
    {
        // unset the owning side of the relation if necessary
        if ($userInfo === null && $this->userInfo !== null) {
            $this->userInfo->setUserPicture(null);
        }

        // set the owning side of the relation if necessary
        if ($userInfo !== null && $userInfo->getUserPicture() !== $this) {
            $userInfo->setUserPicture($this);
        }

        $this->userInfo = $userInfo;

        return $this;
    }
}
