<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StrainSourceTagRepository")
 */
class StrainSourceTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\StrainSource", inversedBy="strainSourceTags")
     */
    private $strainSources;

    public function __construct()
    {
        $this->strainSources = new ArrayCollection();
    }

    public function __toString(): ?string
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
        }

        return $this;
    }

    public function removeStrainSource(StrainSource $strainSource): self
    {
        if ($this->strainSources->contains($strainSource)) {
            $this->strainSources->removeElement($strainSource);
        }

        return $this;
    }
}
