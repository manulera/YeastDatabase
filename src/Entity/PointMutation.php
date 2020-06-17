<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointMutationRepository")
 */
class PointMutation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("allele")
     * @ORM\Column(type="string", length=1)
     */
    private $originalAminoAcid;

    /**
     * @Groups("allele")
     * @ORM\Column(type="string", length=1)
     */
    private $newAminoAcid;

    /**
     * @Groups("allele")
     * @ORM\Column(type="integer")
     */
    private $sequencePosition;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AlleleChunky", mappedBy="pointMutations")
     */
    private $alleleChunkies;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $originalCodon;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $newCodon;

    public function __construct()
    {
        $this->alleleChunkies = new ArrayCollection();
    }
    public function __toString(): string
    {
        $a = $this->getOriginalAminoAcid();
        $b = $this->getNewAminoAcid();
        $i = $this->getSequencePosition();
        return "$a$i$b";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalAminoAcid(): ?string
    {
        return $this->originalAminoAcid;
    }

    public function setOriginalAminoAcid(string $originalAminoAcid): self
    {
        $this->originalAminoAcid = $originalAminoAcid;

        return $this;
    }

    public function getNewAminoAcid(): ?string
    {
        return $this->newAminoAcid;
    }

    public function setNewAminoAcid(string $newAminoAcid): self
    {
        $this->newAminoAcid = $newAminoAcid;

        return $this;
    }

    public function getSequencePosition(): ?int
    {
        return $this->sequencePosition;
    }

    public function setSequencePosition(int $sequencePosition): self
    {
        $this->sequencePosition = $sequencePosition;

        return $this;
    }

    /**
     * @return Collection|AlleleChunky[]
     */
    public function getAlleleChunkies(): Collection
    {
        return $this->alleleChunkies;
    }

    public function addAlleleChunky(AlleleChunky $alleleChunky): self
    {
        if (!$this->alleleChunkies->contains($alleleChunky)) {
            $this->alleleChunkies[] = $alleleChunky;
            $alleleChunky->addPointMutation($this);
        }

        return $this;
    }

    public function removeAlleleChunky(AlleleChunky $alleleChunky): self
    {
        if ($this->alleleChunkies->contains($alleleChunky)) {
            $this->alleleChunkies->removeElement($alleleChunky);
            $alleleChunky->removePointMutation($this);
        }

        return $this;
    }

    public function getOriginalCodon(): ?string
    {
        return $this->originalCodon;
    }

    public function setOriginalCodon(?string $originalCodon): self
    {
        $this->originalCodon = $originalCodon;

        return $this;
    }

    public function getNewCodon(): ?string
    {
        return $this->newCodon;
    }

    public function setNewCodon(?string $newCodon): self
    {
        $this->newCodon = $newCodon;

        return $this;
    }

    
}
