<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\JoinColumn;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleDeletionRepository")
 */
class AlleleDeletion extends Allele
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marker")
     * @JoinColumn(name="marker_name", referencedColumnName="name")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marker;

    public function getMarker(): ?Marker
    {
        return $this->marker;
    }

    public function setMarker(?Marker $marker): self
    {
        $this->marker = $marker;

        return $this;
    }

    public function updateName()
    {
        $this->setName($this->getLocus() . "Δ::" . $this->marker);
    }
}
