<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleChunkyRepository")
 */
class AlleleChunky extends Allele
{

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Promoter")
     * @JoinColumn(name="promoter_name", referencedColumnName="name")
     */
    private $promoter;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @JoinColumn(name="nTag_name", referencedColumnName="name")
     */
    private $nTag;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @JoinColumn(name="cTag_name", referencedColumnName="name")
     */
    private $cTag;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker")
     * @JoinColumn(name="nMarker_name", referencedColumnName="name")
     */
    private $nMarker;

    /**
     * @Groups("allele")
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker")
     * @JoinColumn(name="cMarker_name", referencedColumnName="name")
     */
    private $cMarker;

    /**
     * @Groups("allele")
     * @ORM\ManyToMany(targetEntity="App\Entity\PointMutation", inversedBy="alleleChunkies",cascade={"persist", "remove"})
     */
    private $pointMutations;

    // TODO handle properly the truncations and point mutations that can be repeated
    /**
     * @Groups("allele")
     * @ORM\ManyToMany(targetEntity="App\Entity\Truncation", inversedBy="alleleChunkies",cascade={"persist", "remove"})
     */
    private $truncations;

    public function __construct()
    {
        parent::__construct();
        $this->pointMutations = new ArrayCollection();
        $this->truncations = new ArrayCollection();
    }

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

        $locus = $this->getLocus();

        if ($locus->getName()) {
            $name .= $locus->getName();
        } else {
            $name .= $locus->getPombaseId();
        }
        if (count($this->getPointMutations())) {
            $pm_name = [];
            foreach ($this->getPointMutations() as $pm) {
                $pm_name[] = strval($pm);
            }
            $pm_name = implode(",", $pm_name);
            $name .= "($pm_name)";
        }


        foreach ($this->getTruncations() as $truncation) {
            $name .= strval($truncation);
        }

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
        }

        return $this;
    }

    public function removePointMutation(PointMutation $pointMutation): self
    {
        if ($this->pointMutations->contains($pointMutation)) {
            $this->pointMutations->removeElement($pointMutation);
        }

        return $this;
    }

    /**
     * @return Collection|Truncation[]
     */
    public function getTruncations(): Collection
    {
        return $this->truncations;
    }

    public function addTruncation(Truncation $truncation): self
    {
        if (!$this->truncations->contains($truncation)) {
            $this->truncations[] = $truncation;
        }

        return $this;
    }

    public function removeTruncation(Truncation $truncation): self
    {
        if ($this->truncations->contains($truncation)) {
            $this->truncations->removeElement($truncation);
        }

        return $this;
    }
}
