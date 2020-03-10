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
     * @ORM\OneToMany(targetEntity="App\Entity\MolBiol", mappedBy="inputStrain", orphanRemoval=true)
     */
    private $molBiolInput;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Mating", mappedBy="strains")
     */
    private $matings;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Allele", inversedBy="strains")
     */
    private $alleles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mType;

    public function __construct()
    {
        $this->molBiolInput = new ArrayCollection();
        $this->matings = new ArrayCollection();
        $this->alleles = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO understand why this is necessary
        return strval($this->id) . " - " . $this->getGenotype();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|MolBiol[]
     */
    public function getMolBiolInput(): Collection
    {
        return $this->molBiolInput;
    }

    public function addMolBiolInput(MolBiol $molBiolInput): self
    {
        if (!$this->molBiolInput->contains($molBiolInput)) {
            $this->molBiolInput[] = $molBiolInput;
            $molBiolInput->setInputStrain($this);
        }

        return $this;
    }

    public function removeMolBiolInput(MolBiol $molBiolInput): self
    {
        if ($this->molBiolInput->contains($molBiolInput)) {
            $this->molBiolInput->removeElement($molBiolInput);
            // set the owning side to null (unless already changed)
            if ($molBiolInput->getInputStrain() === $this) {
                $molBiolInput->setInputStrain(null);
            }
        }

        return $this;
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
        $this->setGenotype($genotyper->getGenotype($this->getAlleles()));
    }



    /**
     * @return Collection|Mating[]
     */
    public function getMatings(): Collection
    {
        return $this->matings;
    }

    public function addMating(Mating $mating): self
    {
        if (!$this->matings->contains($mating)) {
            $this->matings[] = $mating;
            $mating->addStrain($this);
        }

        return $this;
    }

    public function removeMating(Mating $mating): self
    {
        if ($this->matings->contains($mating)) {
            $this->matings->removeElement($mating);
            $mating->removeStrain($this);
        }

        return $this;
    }

    public function matingPossibilities(Strain $strain2)
    {
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
}
