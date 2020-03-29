<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $start;

    /**
     * @ORM\Column(type="integer")
     */
    private $finish;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Allele", inversedBy="truncations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $allele;

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

    public function getAllele(): ?Allele
    {
        return $this->allele;
    }

    public function setAllele(?Allele $allele): self
    {
        $this->allele = $allele;

        return $this;
    }
}
