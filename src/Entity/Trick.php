<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity('name', message: "Une figure avec ce nom existe déjà.")]
class Trick
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le nom de la figure.")]
    #[Assert\Length(
        min: 6,
        minMessage: "Le nom de la figure doit avoir au moins {{ limit }} caractères.",
        max: 50,
        maxMessage: "Le nom de la figure doit avoir au plus {{ limit }} caractères.",
    )]
    private ?string $name = null;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Veuillez renseigner la description de votre figure.")]
    #[Assert\Length(
        min: 20,
        minMessage: "La description de la figure doit avoir au moins {{ limit }} caractères.",
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickPicture::class, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickVideo::class, cascade: ['persist', 'remove'], orphanRemoval:true)]
    private Collection $video;

    #[ORM\ManyToOne(targetEntity: TrickCategory::class, inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrickCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickComment::class, cascade: ['persist', 'remove'])]
    private ?Collection $comments = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();

        $this->pictures  = new ArrayCollection();
        $this->video     = new ArrayCollection();
        $this->comments  = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, TrickPicture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(TrickPicture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(TrickPicture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // Set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }

    public function getMainPicture(): ?TrickPicture
    {
        $mainPicture = null;
        foreach ($this->getPictures() as $trickPicture) {
            if ($trickPicture->isMain()) {
                $mainPicture = $trickPicture;
            }
        }
        return $mainPicture;

    }

    public function setMainPicture(TrickPicture $picture): static
    {
        // reset main picture from this $picture if it exists trick before set this new one as main picture
        if ($picture->getTrick()->getMainPicture() != null) {
            $picture->getTrick()->getMainPicture()->setMain(false);
        }

        $picture->setMain(true);
        return $this;
    }

    /**
     * @return Collection<int, TrickVideo>
     */
    public function getVideo(): Collection
    {
        return $this->video;
    }

    public function addVideo(TrickVideo $video): static
    {
        if (!$this->video->contains($video)) {
            $this->video->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(TrickVideo $video): static
    {
        if ($this->video->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?TrickCategory
    {
        return $this->category;
    }

    public function setCategory(?TrickCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, TrickComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(TrickComment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(TrickComment $comment): static
    {
        if ($this->comments->removeElement($comment) == true) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
