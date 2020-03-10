<?php

namespace App\Entity;

use App\Form\MatingType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatingRepository")
 */
class Mating extends StrainSource
{

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", inversedBy="matings")
     */
    private $strains;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain2;

    public function __construct()
    {
        StrainSource::__construct();
        $this->name = "Mating Strains";
        $this->strains = new ArrayCollection();
    }

    /**
     * @return Collection|Strain[]
     */
    public function getStrains(): Collection
    {
        return $this->strains;
    }

    public function addStrain(Strain $strain): self
    {
        if (!$this->strains->contains($strain)) {
            $this->strains[] = $strain;
        }

        return $this;
    }

    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    public function getStrain1(): ?Strain
    {
        return $this->strain1;
    }

    public function setStrain1(?Strain $strain1): self
    {
        $this->strain1 = $strain1;

        return $this;
    }

    public function getStrain2(): ?Strain
    {
        return $this->strain2;
    }

    public function setStrain2(?Strain $strain2): self
    {
        $this->strain2 = $strain2;

        return $this;
    }
}
