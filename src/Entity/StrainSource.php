<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;


/**
 * @ORM\Entity(repositoryClass="App\Repository\StrainSourceRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "molbiol" = "MolBiol",
 *      "custom" = "CustomStrainSource",
 *      "deletionBahlerMethod" = "DeletionBahlerMethod",
 *      "mating" = "Mating"
 * })
 */
abstract class StrainSource
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

    // The associated form class

    // @var string
    protected $formClass;

    // @var string
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Allele", mappedBy="strainSource")
     */
    protected $alleles;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="strainSources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    public function __construct()
    {
        $this->strainsOut = new ArrayCollection();
        $this->alleles = new ArrayCollection();
    }


    public function createStrains(Form $form, array $options = [])
    {
    }

    public function createAlleles(Form $form)
    {
    }

    public function getFormClass()
    {
        return $this->formClass;
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

    /**
     * @return Collection|Allele[]
     */
    public function getAlleles(): Collection
    {
        return $this->alleles;
    }

    public function addAllele(Allele $allele): self
    {
        if (!$this->alleles->contains($allele)) {
            $this->alleles[] = $allele;
            $allele->setStrainSource($this);
        }

        return $this;
    }

    public function removeAllele(Allele $allele): self
    {
        if ($this->alleles->contains($allele)) {
            $this->alleles->removeElement($allele);
            // set the owning side to null (unless already changed)
            if ($allele->getStrainSource() === $this) {
                $allele->setStrainSource(null);
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
}
