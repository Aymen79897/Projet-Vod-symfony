<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username ;

    #[ORM\Column(length: 255)]
    private ?string $password ;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    private  ?string $plainPassword;

    #[ORM\Column(type: 'json')]
    private  $roles = [];

    #[ORM\OneToMany(mappedBy: 'Comment', targetEntity: Comment::class)]
    private Collection $Comment;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favorite::class)]
    private Collection $favorites;

    #[ORM\Column]
    private ?bool $banned = false;




    public function __construct()
    {

        $this->Comment = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }



    /**
     * @return Collection<int, Comment>
     */


    /**
     * @return Collection<int, Favorite>
     */


    public function getRoles(): array
    {
        $roles = $this->roles;

        // Ensure every user has the ROLE_USER role
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function eraseCredentials()
    {
        $this->plainPassword = null;}

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getComment(): Collection
    {
        return $this->Comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->Comment->contains($comment)) {
            $this->Comment->add($comment);
            $comment->setComment($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->Comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getComment() === $this) {
                $comment->setComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUser($this);
        }

        return $this;
    }
    public function hasFavorite(Video $video): bool
    {
        foreach ($this->favorites as $favorite) {
            if ($favorite->getVideo() === $video) {
                return true;
            }
        }
        return false;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
}
