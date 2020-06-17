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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=100,unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\StrainSource", mappedBy="plasmids")
     */
    private $strainSources;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", mappedBy="plasmids")
     */
    private $strains;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    public function __construct()
    {
        $this->strainSources = new ArrayCollection();
        $this->strains = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->code . ' - ' . $this->name;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|StrainSource[]
     */
    public function getStrainSources(): Collection
    {
        return $this->strainSources;
    }

    public function addStrainSource(StrainSource $strainSource): self
    {
        if (!$this->strainSources->contains($strainSource)) {
            $this->strainSources[] = $strainSource;
            $strainSource->addPlasmid($this);
        }

        return $this;
    }

    public function removeStrainSource(StrainSource $strainSource): self
    {
        if ($this->strainSources->contains($strainSource)) {
            $this->strainSources->removeElement($strainSource);
            $strainSource->removePlasmid($this);
        }

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
            $strain->addPlasmid($this);
        }

        return $this;
    }

    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
            $strain->removePlasmid($this);
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }
}
