<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OligoRepository")
 */
class Oligo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sequence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeletionBahlerMethod", mappedBy="primerForward", orphanRemoval=true)
     */
    private $deletionBahlerMethodsPrimerForward;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeletionBahlerMethod", mappedBy="primerReverse", orphanRemoval=true)
     */
    private $deletionBahlerMethodsPrimerReverse;

    public function __construct()
    {
        $this->deletionBahlerMethodsPrimerForward = new ArrayCollection();
        $this->deletionBahlerMethodsPrimerReverse = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSequence(): ?string
    {
        return $this->sequence;
    }

    public function setSequence(string $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection|DeletionBahlerMethod[]
     */
    public function getDeletionBahlerMethodsPrimerForward(): Collection
    {
        return $this->deletionBahlerMethodsPrimerForward;
    }

    public function addDeletionBahlerMethodsPrimerForward(DeletionBahlerMethod $deletionBahlerMethodsPrimerForward): self
    {
        if (!$this->deletionBahlerMethodsPrimerForward->contains($deletionBahlerMethodsPrimerForward)) {
            $this->deletionBahlerMethodsPrimerForward[] = $deletionBahlerMethodsPrimerForward;
            $deletionBahlerMethodsPrimerForward->setPrimerForward($this);
        }

        return $this;
    }

    public function removeDeletionBahlerMethodsPrimerForward(DeletionBahlerMethod $deletionBahlerMethodsPrimerForward): self
    {
        if ($this->deletionBahlerMethodsPrimerForward->contains($deletionBahlerMethodsPrimerForward)) {
            $this->deletionBahlerMethodsPrimerForward->removeElement($deletionBahlerMethodsPrimerForward);
            // set the owning side to null (unless already changed)
            if ($deletionBahlerMethodsPrimerForward->getPrimerForward() === $this) {
                $deletionBahlerMethodsPrimerForward->setPrimerForward(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DeletionBahlerMethod[]
     */
    public function getDeletionBahlerMethodsPrimerReverse(): Collection
    {
        return $this->deletionBahlerMethodsPrimerReverse;
    }

    public function addDeletionBahlerMethodsPrimerReverse(DeletionBahlerMethod $deletionBahlerMethodsPrimerReverse): self
    {
        if (!$this->deletionBahlerMethodsPrimerReverse->contains($deletionBahlerMethodsPrimerReverse)) {
            $this->deletionBahlerMethodsPrimerReverse[] = $deletionBahlerMethodsPrimerReverse;
            $deletionBahlerMethodsPrimerReverse->setPrimerReverse($this);
        }

        return $this;
    }

    public function removeDeletionBahlerMethodsPrimerReverse(DeletionBahlerMethod $deletionBahlerMethodsPrimerReverse): self
    {
        if ($this->deletionBahlerMethodsPrimerReverse->contains($deletionBahlerMethodsPrimerReverse)) {
            $this->deletionBahlerMethodsPrimerReverse->removeElement($deletionBahlerMethodsPrimerReverse);
            // set the owning side to null (unless already changed)
            if ($deletionBahlerMethodsPrimerReverse->getPrimerReverse() === $this) {
                $deletionBahlerMethodsPrimerReverse->setPrimerReverse(null);
            }
        }

        return $this;
    }
}
