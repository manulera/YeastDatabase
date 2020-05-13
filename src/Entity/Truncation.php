<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TruncationRepository")
 */
class Truncation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("allele")
     * @ORM\Column(type="integer")
     */
    private $start;

    /**
     * @Groups("allele")
     * @ORM\Column(type="integer")
     */
    private $finish;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AlleleChunky", mappedBy="truncations")
     */
    private $alleleChunkies;

    public function __toString(): string
    {
        $a = $this->getStart();
        $b = $this->getFinish();
        return  "Δ$a-$b";
    }

    public function __construct()
    {
        $this->alleleChunkies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): ?int
    {
        return $this->finish;
    }

    public function setFinish(int $finish): self
    {
        $this->finish = $finish;

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
            $alleleChunky->addTruncation($this);
        }

        return $this;
    }

    public function removeAlleleChunky(AlleleChunky $alleleChunky): self
    {
        if ($this->alleleChunkies->contains($alleleChunky)) {
            $this->alleleChunkies->removeElement($alleleChunky);
            $alleleChunky->removeTruncation($this);
        }

        return $this;
    }
}
