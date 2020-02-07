<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleRepository")
 */
class Allele
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="allele")
     * @JoinColumn(name="locus_name", referencedColumnName="name")
     */
    private $locus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag", inversedBy="allele")
     * @JoinColumn(name="tag_name", referencedColumnName="name")
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker", inversedBy="allele")
     * @JoinColumn(name="marker_name", referencedColumnName="name")
     */
    private $marker;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", mappedBy="allele")
     */
    private $strains;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StrainSource", inversedBy="alleles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strainSource;


    public function __construct()
    {
        $this->strains = new ArrayCollection();
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

    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setLocus(?Locus $locus): self
    {
        $this->locus = $locus;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getMarker(): ?Marker
    {
        return $this->marker;
    }

    public function setMarker(?Marker $marker): self
    {
        $this->marker = $marker;

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



    public function getStrainSource(): ?StrainSource
    {
        return $this->strainSource;
    }

    public function setStrainSource(?StrainSource $strainSource): self
    {
        $this->strainSource = $strainSource;

        return $this;
    }
}
