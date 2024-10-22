<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "deletion" = "AlleleDeletion",
 *      "chunky" = "AlleleChunky"
 * })
 */
abstract class Allele
{
    /**
     * @Groups("allele")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("allele")
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="allele")
     */
    private $locus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", mappedBy="alleles")
     */
    private $strains;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity=Allele::class)
     */
    private $parentAllele;

    /**
     * @ORM\ManyToOne(targetEntity=StrainSource::class, inversedBy="allelesOut")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strainSource;

    /**
     * @ORM\ManyToMany(targetEntity=StrainSource::class, mappedBy="allelesIn")
     */
    private $strainSourcesIn;



    public function __construct()
    {
        $this->strains = new ArrayCollection();
        $this->strainSourcesIn = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function updateName()
    {
    }


    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setLocus(?Locus $locus): self
    {
        $this->locus = $locus;

        return $this;
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
            $strain->addAllele($this);
        }

        return $this;
    }

    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
            $strain->removeAllele($this);
        }

        return $this;
    }

    public function hasMarker(): bool
    {
        return false;
    }

    public function getAssociatedForm(): string
    {
        return "";
    }

    public function __clone()
    {
        $this->strains = null;
        $this->id = null;
    }

    public function getParentAllele(): ?self
    {
        return $this->parentAllele;
    }

    public function setParentAllele(?self $parentAllele): self
    {
        $this->parentAllele = $parentAllele;

        return $this;
    }

    public function getStrainSource(): ?StrainSource
    {
        return $this->strainSource;
    }

    public function setStrainSource(?StrainSource $strainSource): self
    {
        $this->strainSource = $strainSource;

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
            $strainSourcesIn->addAllelesIn($this);
        }

        return $this;
    }

    public function removeStrainSourcesIn(StrainSource $strainSourcesIn): self
    {
        if ($this->strainSourcesIn->contains($strainSourcesIn)) {
            $this->strainSourcesIn->removeElement($strainSourcesIn);
            $strainSourcesIn->removeAllelesIn($this);
        }

        return $this;
    }
}
