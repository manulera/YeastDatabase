<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "deletion" = "AlleleDeletion",
 *      "chunky" = "AlleleChunky"
 * })
 */
abstract class Allele
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locus", inversedBy="allele")
     * @JoinColumn(name="locus_name", referencedColumnName="name")
     */
    private $locus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StrainSource", inversedBy="alleles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strainSource;


    public function __construct()
    {
        $this->strains = new ArrayCollection();
    }

    public function __toString()
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

    public function getStrainSource(): ?StrainSource
    {
        return $this->strainSource;
    }

    public function setStrainSource(?StrainSource $strainSource): self
    {
        $this->strainSource = $strainSource;

        return $this;
    }

    public function updateName()
    {
    }

    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setLocus(?Locus $locus): self
    {
        $this->locus = $locus;

        return $this;
    }
}
