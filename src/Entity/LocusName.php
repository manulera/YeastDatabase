<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocusNameRepository")
 */
class LocusName
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Locus", mappedBy="name", cascade={"persist", "remove"})
     */
    private $locus;

    public function __toString()
    {
        return $this->getName();
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

    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setLocus(?Locus $locus): self
    {
        $this->locus = $locus;

        // set (or unset) the owning side of the relation if necessary
        $newName = null === $locus ? null : $this;
        if ($locus->getName() !== $newName) {
            $locus->setName($newName);
        }

        return $this;
    }
}
