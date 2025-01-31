<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StrainSourceRepository")
 */
class StrainSource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Strain", mappedBy="source", orphanRemoval=true)
     */
    protected $strainsOut;

    // @var string
    protected $name;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="strainSources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;


    /**
     * @Groups("allele")
     * @ORM\ManyToMany(targetEntity="App\Entity\Plasmid", inversedBy="strainSources")
     */
    private $plasmids;

    /**
     * @Groups("allele")
     * @ORM\ManyToMany(targetEntity="App\Entity\Oligo", inversedBy="strainSources")
     */
    private $oligos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", inversedBy="strainSourcesIn")
     */
    private $strainsIn;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\StrainSourceTag", mappedBy="strainSources")
     */
    private $strainSourceTags;

    /**
     * @ORM\OneToMany(targetEntity=Allele::class, mappedBy="strainSource")
     */
    private $allelesOut;

    /**
     * @ORM\ManyToMany(targetEntity=Allele::class, inversedBy="strainSourcesIn")
     */
    private $allelesIn;

    public function __construct()
    {
        $this->strainsOut = new ArrayCollection();
        $this->plasmids = new ArrayCollection();
        $this->oligos = new ArrayCollection();
        $this->strainsIn = new ArrayCollection();
        $this->strainSourceTags = new ArrayCollection();
        $this->allelesOut = new ArrayCollection();
        $this->allelesIn = new ArrayCollection();
    }

    public function __toString()
    {
        $tag = $this->getStrainSourceTags()[0];
        $strains_in = $this->getStrainsIn();
        switch ($tag) {
            case "Import":
                return "Imported externally";
            case "Mating":
                $id1 = $strains_in[0]->getId();
                $id2 = $strains_in[1]->getId();
                return $id1 . "x$id2 mating";
            case "MolBiol":
                $id1 = $strains_in[0]->getId();
                return "$id1 transformed";
            case "Plasmid":
                $id1 = $strains_in[0]->getId();
                $plasmid = $this->getPlasmids()[0];
                return "Plasmid $plasmid into $id1";
            case "MarkerSwitch":
                $id1 = $strains_in[0]->getId();
                return "Marker switch in $id1";
        }
    }

    public function getInput()
    {
        return [];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Strain[]
     */
    public function getStrainsOut(): Collection
    {
        return $this->strainsOut;
    }

    public function addStrainsOut(Strain $strainsOut): self
    {
        if (!$this->strainsOut->contains($strainsOut)) {
            $this->strainsOut[] = $strainsOut;
            $strainsOut->setSource($this);
        }

        return $this;
    }

    public function removeStrainsOut(Strain $strainsOut): self
    {
        if ($this->strainsOut->contains($strainsOut)) {
            $this->strainsOut->removeElement($strainsOut);
            // set the owning side to null (unless already changed)
            if ($strainsOut->getSource() === $this) {
                $strainsOut->setSource(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection|Plasmid[]
     */
    public function getPlasmids(): Collection
    {
        return $this->plasmids;
    }

    public function addPlasmid(Plasmid $plasmid): self
    {
        if (!$this->plasmids->contains($plasmid)) {
            $this->plasmids[] = $plasmid;
        }

        return $this;
    }

    public function removePlasmid(Plasmid $plasmid): self
    {
        if ($this->plasmids->contains($plasmid)) {
            $this->plasmids->removeElement($plasmid);
        }

        return $this;
    }

    /**
     * @return Collection|Oligo[]
     */
    public function getOligos(): Collection
    {
        return $this->oligos;
    }

    public function addOligo(Oligo $oligo): self
    {
        if (!$this->oligos->contains($oligo)) {
            $this->oligos[] = $oligo;
        }

        return $this;
    }

    public function removeOligo(Oligo $oligo): self
    {
        if ($this->oligos->contains($oligo)) {
            $this->oligos->removeElement($oligo);
        }

        return $this;
    }

    /**
     * @return Collection|Strain[]
     */
    public function getStrainsIn(): Collection
    {
        return $this->strainsIn;
    }

    public function addStrainsIn(Strain $strainsIn): self
    {
        if (!$this->strainsIn->contains($strainsIn)) {
            $this->strainsIn[] = $strainsIn;
        }

        return $this;
    }

    public function removeStrainsIn(Strain $strainsIn): self
    {
        if ($this->strainsIn->contains($strainsIn)) {
            $this->strainsIn->removeElement($strainsIn);
        }

        return $this;
    }

    /**
     * @return Collection|StrainSourceTag[]
     */
    public function getStrainSourceTags(): Collection
    {
        return $this->strainSourceTags;
    }

    public function addStrainSourceTag(StrainSourceTag $strainSourceTag): self
    {
        if (!$this->strainSourceTags->contains($strainSourceTag)) {
            $this->strainSourceTags[] = $strainSourceTag;
            $strainSourceTag->addStrainSource($this);
        }

        return $this;
    }

    public function removeStrainSourceTag(StrainSourceTag $strainSourceTag): self
    {
        if ($this->strainSourceTags->contains($strainSourceTag)) {
            $this->strainSourceTags->removeElement($strainSourceTag);
            $strainSourceTag->removeStrainSource($this);
        }

        return $this;
    }

    /**
     * @return Collection|Allele[]
     */
    public function getAllelesOut(): Collection
    {
        return $this->allelesOut;
    }

    public function addAllelesOut(Allele $allelesOut): self
    {
        if (!$this->allelesOut->contains($allelesOut)) {
            $this->allelesOut[] = $allelesOut;
            $allelesOut->setStrainSource($this);
        }

        return $this;
    }

    public function removeAllelesOut(Allele $allelesOut): self
    {
        if ($this->allelesOut->contains($allelesOut)) {
            $this->allelesOut->removeElement($allelesOut);
            // set the owning side to null (unless already changed)
            if ($allelesOut->getStrainSource() === $this) {
                $allelesOut->setStrainSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Allele[]
     */
    public function getAllelesIn(): Collection
    {
        return $this->allelesIn;
    }

    public function addAllelesIn(Allele $allelesIn): self
    {
        if (!$this->allelesIn->contains($allelesIn)) {
            $this->allelesIn[] = $allelesIn;
        }

        return $this;
    }

    public function removeAllelesIn(Allele $allelesIn): self
    {
        if ($this->allelesIn->contains($allelesIn)) {
            $this->allelesIn->removeElement($allelesIn);
        }

        return $this;
    }
}
