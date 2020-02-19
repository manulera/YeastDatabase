<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromoterRepository")
 */
class Promoter
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\allele", mappedBy="promoter")
     */
    private $allele;


    public function __construct()
    {
        $this->allele = new ArrayCollection();
    }

    /**
     * @return Collection|allele[]
     */
    public function getAllele(): Collection
    {
        return $this->allele;
    }

    public function addAllele(allele $allele): self
    {
        if (!$this->allele->contains($allele)) {
            $this->allele[] = $allele;
            $allele->setPromoter($this);
        }

        return $this;
    }

    public function removeAllele(allele $allele): self
    {
        if ($this->allele->contains($allele)) {
            $this->allele->removeElement($allele);
            // set the owning side to null (unless already changed)
            if ($allele->getPromoter() === $this) {
                $allele->setPromoter(null);
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
