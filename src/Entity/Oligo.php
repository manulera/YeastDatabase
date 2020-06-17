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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=100,unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sequence;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\StrainSource", mappedBy="oligos")
     */
    private $strainSources;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $details;

    public function __construct()
    {
        $this->strainSources = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name . " - " . $this->details;
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
            $strainSource->addOligo($this);
        }

        return $this;
    }

    public function removeStrainSource(StrainSource $strainSource): self
    {
        if ($this->strainSources->contains($strainSource)) {
            $this->strainSources->removeElement($strainSource);
            $strainSource->removeOligo($this);
        }

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
