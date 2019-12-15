<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlasmidRepository")
 */
class Plasmid
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeletionBahlerMethod", mappedBy="plasmid", orphanRemoval=true)
     */
    private $deletionBahlerMethods;


    public function __construct()
    {
        $this->deletionBahlerMethods = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection|DeletionBahlerMethod[]
     */
    public function getDeletionBahlerMethods(): Collection
    {
        return $this->deletionBahlerMethods;
    }

    public function addDeletionBahlerMethod(DeletionBahlerMethod $deletionBahlerMethod): self
    {
        if (!$this->deletionBahlerMethods->contains($deletionBahlerMethod)) {
            $this->deletionBahlerMethods[] = $deletionBahlerMethod;
            $deletionBahlerMethod->setPlasmid($this);
        }

        return $this;
    }

    public function removeDeletionBahlerMethod(DeletionBahlerMethod $deletionBahlerMethod): self
    {
        if ($this->deletionBahlerMethods->contains($deletionBahlerMethod)) {
            $this->deletionBahlerMethods->removeElement($deletionBahlerMethod);
            // set the owning side to null (unless already changed)
            if ($deletionBahlerMethod->getPlasmid() === $this) {
                $deletionBahlerMethod->setPlasmid(null);
            }
        }

        return $this;
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
}
