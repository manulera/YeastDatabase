<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TempStrainRepository")
 */
class TempStrain implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Allele")
     */
    private $allele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $genotype;

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->allele,
            $this->genotype,
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->id,
            $this->alle,
            $this->genotype
        ) = unserialize($data);
    }

    public function __construct()
    {
        $this->allele = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Allele[]
     */
    public function getAllele(): Collection
    {
        return $this->allele;
    }

    public function addAllele(Allele $allele): self
    {
        if (!$this->allele->contains($allele)) {
            $this->allele[] = $allele;
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->allele->contains($allele)) {
            $this->allele->removeElement($allele);
        }

        return $this;
    }

    public function getGenotype(): ?string
    {
        return $this->genotype;
    }

    public function setGenotype(?string $genotype): self
    {
        $this->genotype = $genotype;

        return $this;
    }

    public function __toString()
    {
        // TODO understand why this is necessary
        $this->updateGenotype();
        return strval($this->id) . " - " . $this->getGenotype();
    }

    public function updateGenotype(): void
    {
        $genotype_array = [];

        foreach ($this->getAllele() as $allele) {
            $genotype_array[] = $allele->getName();
        }
        // Some sorting function
        $genotype_array = $this->sortGenotype($genotype_array);
        $this->genotype = implode(' ', $genotype_array);
    }

    public function sortGenotype(?array $genotype_array)
    {
        return $genotype_array;
    }
}
