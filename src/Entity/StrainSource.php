<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StrainSourceRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "molbiol" = "MolBiol",
 *      "custom" = "CustomStrainSource"
 * })
 */
abstract class StrainSource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Strain", mappedBy="source", orphanRemoval=true)
     */
    private $strainsOut;

    // The associated form class

    // @var string
    protected $formClass;

    // @var string
    protected $name;

    public function __construct()
    {
        $this->strainsOut = new ArrayCollection();
    }

    public function createStrains()
    { }

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
}
