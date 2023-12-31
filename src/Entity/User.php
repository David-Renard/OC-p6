<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email',message: "Un compte avec cette adresse mail existe déjà.")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Email(message: "Ceci ne correspond pas à une adresse mail.")]
    #[Assert\NotBlank(message: "Veuillez saisir un email.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Trick::class)]
    private Collection $tricks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TrickComment::class)]
    private Collection $comments;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Veuillez saisir un login.")]
    #[Assert\Length(
        min: 6,
        minMessage: "Votre login doit avoir au moins {{ limit }} caractères.",
        max: 50,
        maxMessage: "Votre login doit avoir au plus {{ limit }} caractères.",
    )]
    private ?string $username = null;

    #[Assert\NotBlank(message: "Veuillez saisir un mot de passe.")]
    #[Assert\PasswordStrength(
        [
            "minScore" => Assert\PasswordStrength::STRENGTH_WEAK,
            "message"  => "Votre mot de passe est trop simple. Pour votre sécurité, changez-le."
        ]
    )]
    #[Assert\Length(
        min: 6,
        minMessage: "Votre mot de passe doit avoir au moins {{ limit }} caractères.",
        max: 50,
        maxMessage: "Votre mot de passe doit avoir au plus {{ limit }} caractères.",
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\OneToOne(inversedBy: 'userInfo', cascade: ['persist', 'remove'])]
    private ?UserPicture $userPicture = null;

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;


    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;


    }


    public function __construct()
    {


        $this->tricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;


    }

    public function getEmail(): ?string
    {
        return $this->email;


    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;


    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;


    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);


    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;


    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;


    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;


    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;


    }

    /**
     * @return Collection<int, Trick>
     */
    public function getTricks(): Collection
    {
        return $this->tricks;


    }

    public function addTrick(Trick $trick): static
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
            $trick->setAuthor($this);
        }

        return $this;


    }

    public function removeTrick(Trick $trick): static
    {
        if ($this->tricks->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getAuthor() === $this) {
                $trick->setAuthor(null);
            }
        }

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
            $comment->setUser($this);
        }

        return $this;


    }

    public function removeComment(TrickComment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // Set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;


    }

    public function getUsername(): ?string
    {
        return $this->username;


    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;


    }

    public function isVerified(): bool
    {
        return $this->isVerified;


    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;


    }

    public function getUserPicture(): ?UserPicture
    {
        return $this->userPicture;


    }

    public function setUserPicture(?UserPicture $userPicture): static
    {
        $this->userPicture = $userPicture;

        return $this;


    }
}
