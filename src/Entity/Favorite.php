<?php

namespace App\Entity;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?Video $Video = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideo(): ?Video
    {
        return $this->Video;
    }

    public function setVideo(?Video $Video): self
    {
        $this->Video = $Video;

        return $this;
    }

    public function getUser(): \App\Entity\User
    {
        return $this->User;
    }

    public function setUser(\App\Entity\User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
