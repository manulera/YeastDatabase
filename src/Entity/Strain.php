<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Allele", inversedBy="strains")
     */
    private $allele;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MolBiol", mappedBy="inputStrain", orphanRemoval=true)
     */
    private $molBiolInput;

    public function __construct()
    {
        $this->allele = new ArrayCollection();
        $this->molBiolInput = new ArrayCollection();
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
}
