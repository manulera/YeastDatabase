<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleChunkyRepository")
 */
class AlleleChunky extends Allele
{

    public function __construct()
    {
        $this->pointMutations = new ArrayCollection();
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promoter")
     * @JoinColumn(name="promoter_name", referencedColumnName="name")
     */
    private $promoter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @JoinColumn(name="nTag_name", referencedColumnName="name")
     */
    private $nTag;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @JoinColumn(name="cTag_name", referencedColumnName="name")
     */
    private $cTag;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker")
     * @JoinColumn(name="nMarker_name", referencedColumnName="name")
     */
    private $nMarker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker")
     * @JoinColumn(name="cMarker_name", referencedColumnName="name")
     */
    private $cMarker;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PointMutation", mappedBy="allele")
     */
    private $pointMutations;

    public function getPromoter(): ?Promoter
    {
        return $this->promoter;
    }

    public function setPromoter(?Promoter $promoter): self
    {
        $this->promoter = $promoter;

        return $this;
    }

    public function updateName()
    {
        $name = "";
        if ($this->getNMarker()) {
            $name .= $this->getNMarker() . "::";
        }
        if ($this->getPromoter()) {
            $name .= $this->getPromoter() . "-";
        }
        if ($this->getNTag()) {
            $name .= $this->getNTag() . "-";
        }
        $name .= $this->getLocus();
        if ($this->getCTag()) {
            $name .= "-" . $this->getCTag();
        }
        if ($this->getCMarker()) {
            $name .= "::" . $this->getCMarker();
        }
        $this->setName($name);
    }

    public function getNTag(): ?Tag
    {
        return $this->nTag;
    }

    public function setNTag(?Tag $nTag): self
    {
        $this->nTag = $nTag;

        return $this;
    }

    public function getCTag(): ?Tag
    {
        return $this->cTag;
    }

    public function setCTag(?Tag $cTag): self
    {
        $this->cTag = $cTag;

        return $this;
    }

    public function getNMarker(): ?Marker
    {
        return $this->nMarker;
    }

    public function setNMarker(?Marker $nMarker): self
    {
        $this->nMarker = $nMarker;

        return $this;
    }

    public function getCMarker(): ?Marker
    {
        return $this->cMarker;
    }

    public function setCMarker(?Marker $cMarker): self
    {
        $this->cMarker = $cMarker;

        return $this;
    }

    /**
     * @return Collection|PointMutation[]
     */
    public function getPointMutations(): Collection
    {
        return $this->pointMutations;
    }

    public function addPointMutation(PointMutation $pointMutation): self
    {
        if (!$this->pointMutations->contains($pointMutation)) {
            $this->pointMutations[] = $pointMutation;
            $pointMutation->setAllele($this);
        }

        return $this;
    }

    public function removePointMutation(PointMutation $pointMutation): self
    {
        if ($this->pointMutations->contains($pointMutation)) {
            $this->pointMutations->removeElement($pointMutation);
            // set the owning side to null (unless already changed)
            if ($pointMutation->getAllele() === $this) {
                $pointMutation->setAllele(null);
            }
        }

        return $this;
    }
}
