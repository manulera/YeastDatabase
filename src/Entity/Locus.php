<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocusRepository")
 */
class Locus
{
    // TODO: At some point, one should also check that the locus exists,
    // and refer to it by the appropiate notation, also handle genes that
    // can have two names.

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Allele", mappedBy="locus")
     */
    private $allele;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PombaseId", inversedBy="locus", cascade={"persist", "remove"})
     * @JoinColumn(name="pombase_id", referencedColumnName="pombase_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pombase_id;

    /**
     * @Groups("allele")
     * @ORM\OneToOne(targetEntity="App\Entity\LocusName", inversedBy="locus", cascade={"persist", "remove"})
     * @JoinColumn(name="name", referencedColumnName="name")
     */
    private $name;

    public function __construct()
    {
        $this->allele = new ArrayCollection();
    }

    public function __toString()
    {
        return strval($this->getName()) . " / " . strval($this->getPombaseId());
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
            $allele->setLocus($this);
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->allele->contains($allele)) {
            $this->allele->removeElement($allele);
            // set the owning side to null (unless already changed)
            if ($allele->getLocus() === $this) {
                $allele->setLocus(null);
            }
        }

        return $this;
    }

    public function getPombaseId(): ?PombaseId
    {
        return $this->pombase_id;
    }

    public function setPombaseId(PombaseId $pombase_id): self
    {
        $this->pombase_id = $pombase_id;

        return $this;
    }

    public function getName(): ?LocusName
    {
        return $this->name;
    }

    public function setName(?LocusName $name): self
    {
        $this->name = $name;

        return $this;
    }
}
