<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MolBiolRepository")
 */
class MolBiol extends StrainSource
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="molBiolInput")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $inputStrain;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plasmid")
     */
    private $plasmids;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Oligo")
     */
    private $oligos;


    public function __construct()
    {

        StrainSource::__construct();
        $this->plasmids = new ArrayCollection();
        $this->oligos = new ArrayCollection();
    }

    public function getInputStrain(): ?Strain
    {
        return $this->inputStrain;
    }

    public function setInputStrain(?Strain $inputStrain): self
    {
        $this->inputStrain = $inputStrain;

        return $this;
    }

    /**
     * @return Collection|Plasmid[]
     */
    public function getPlasmids(): Collection
    {
        return $this->plasmids;
    }

    public function addPlasmid(Plasmid $plasmid): self
    {
        if (!$this->plasmids->contains($plasmid)) {
            $this->plasmids[] = $plasmid;
        }

        return $this;
    }

    public function removePlasmid(Plasmid $plasmid): self
    {
        if ($this->plasmids->contains($plasmid)) {
            $this->plasmids->removeElement($plasmid);
        }

        return $this;
    }

    /**
     * @return Collection|Oligo[]
     */
    public function getOligos(): Collection
    {
        return $this->oligos;
    }

    public function addOligo(Oligo $oligo): self
    {
        if (!$this->oligos->contains($oligo)) {
            $this->oligos[] = $oligo;
        }

        return $this;
    }

    public function removeOligo(Oligo $oligo): self
    {
        if ($this->oligos->contains($oligo)) {
            $this->oligos->removeElement($oligo);
        }

        return $this;
    }
}
