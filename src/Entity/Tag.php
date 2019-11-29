<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string",length=100)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Allele", mappedBy="tag")
     */
    private $allele;

    public function __construct()
    {
        $this->allele = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
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
            $allele->setTag($this);
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->allele->contains($allele)) {
            $this->allele->removeElement($allele);
            // set the owning side to null (unless already changed)
            if ($allele->getTag() === $this) {
                $allele->setTag(null);
            }
        }

        return $this;
    }
}
