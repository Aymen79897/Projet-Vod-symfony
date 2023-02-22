<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Video::class)]
    private Collection $Video;



    public function __construct()
    {
        $this->Video = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return Collection<int, Video>
     */
    public function getVideo(): Collection
    {
        return $this->Video;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->Video->contains($video)) {
            $this->Video->add($video);
            $video->setSerie($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->Video->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getSerie() === $this) {
                $video->setSerie(null);
            }
        }

        return $this;
    }

}
