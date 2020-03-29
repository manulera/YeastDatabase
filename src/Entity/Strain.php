<?php

namespace App\Entity;

use App\Service\Genotyper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinColumns;



/**
 * @ORM\Entity(repositoryClass="App\Repository\StrainRepository")
 */
class Strain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $genotype;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StrainSource", inversedBy="strainsOut",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Allele", inversedBy="strains")
     */
    private $alleles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mType;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\StrainSource", mappedBy="strainsIn")
     */
    private $strainSourcesIn;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plasmid", inversedBy="strains")
     */
    private $plasmids;

    public function __construct()
    {
        $this->alleles = new ArrayCollection();
        $this->strainSourcesIn = new ArrayCollection();
        $this->plasmids = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO understand why this is necessary
        return strval($this->id) . " - " . $this->getGenotype();
    }

    public function __clone()
    {
        // We have to deep copy the lists of references, otherwise it will pass them as reference
        // (The cloned strain will point to the same ArrayCollection of alleles, so if they are changed in one,
        // they will also be changed in the other)
        $this->alleles = clone $this->alleles;
        $this->strainSourcesIn = clone $this->strainSourcesIn;
        $this->plasmids = clone $this->plasmids;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?StrainSource
    {
        return $this->source;
    }

    public function setSource(?StrainSource $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getGenotype(): ?string
    {
        return $this->genotype;
    }

    public function setGenotype(?string $genotype): self
    {
        $this->genotype = $this->getMType() . " " . $genotype;

        return $this;
    }

    public function updateGenotype(Genotyper $genotyper): void
    {
        $this->setGenotype($genotyper->getGenotype($this->getAlleles(), $this->getPlasmids()));
    }

    /**
     * @return Collection|Allele[]
     */
    public function getAlleles(): Collection
    {
        return $this->alleles;
    }

    public function addAllele(Allele $allele): self
    {
        if (!$this->alleles->contains($allele)) {
            $this->alleles[] = $allele;
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->alleles->contains($allele)) {
            $this->alleles->removeElement($allele);
        }

        return $this;
    }

    public function getMType(): ?string
    {
        return $this->mType;
    }

    public function setMType(string $mType): self
    {
        $this->mType = $mType;

        return $this;
    }

    /**
     * @return Collection|StrainSource[]
     */
    public function getStrainSourcesIn(): Collection
    {
        return $this->strainSourcesIn;
    }

    public function addStrainSourcesIn(StrainSource $strainSourcesIn): self
    {
        if (!$this->strainSourcesIn->contains($strainSourcesIn)) {
            $this->strainSourcesIn[] = $strainSourcesIn;
            $strainSourcesIn->addStrainsIn($this);
        }

        return $this;
    }

    public function removeStrainSourcesIn(StrainSource $strainSourcesIn): self
    {
        if ($this->strainSourcesIn->contains($strainSourcesIn)) {
            $this->strainSourcesIn->removeElement($strainSourcesIn);
            $strainSourcesIn->removeStrainsIn($this);
        }

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
}
