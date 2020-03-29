<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=1)
     */
    private $originalAminoAcid;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $newAminoAcid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AlleleChunky", inversedBy="pointMutations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $allele;

    /**
     * @ORM\Column(type="integer")
     */
    private $sequencePosition;
    
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

    public function getAllele(): ?Allele
    {
        return $this->allele;
    }

    public function setAllele(?Allele $allele): self
    {
        $this->allele = $allele;

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
}
