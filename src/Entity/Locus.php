<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocusRepository")
 */
class Locus
{
    // TODO: At some point, one should also check that the locus exist,
    // and refer to it by the appropiate notation, also handle genes that
    // can have two names.

    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Allele", mappedBy="locus")
     */
    private $allele;

    public function __construct()
    {
        $this->allele = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection|Allele[]
     */
    public function getAllele(): Collection
    {
        return $this->allele;
    }

    public function addAllele(Allele $allele): self
    {
        if (!$this->allele->contains($allele)) {
            $this->allele[] = $allele;
            $allele->setLocus($this);
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->allele->contains($allele)) {
            $this->allele->removeElement($allele);
            // set the owning side to null (unless already changed)
            if ($allele->getLocus() === $this) {
                $allele->setLocus(null);
            }
        }

        return $this;
    }
}
