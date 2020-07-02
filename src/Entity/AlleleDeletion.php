<?php

namespace App\Entity;

use App\Form\AlleleDeletionType;
use Doctrine\ORM\Mapping\JoinColumn;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlleleDeletionRepository")
 */
class AlleleDeletion extends Allele
{

    /**
     * @Groups("allele")
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
        $locus = $this->getLocus();

        if ($locus->getName()) {
            $name = $locus->getName();
        } else {
            $name = $locus->getPombaseId();
        }

        $this->setName($name . "Δ::" . $this->marker);
    }

    public function hasMarker(): bool
    {
        return boolval($this->getMarker());
    }

    public function getAssociatedForm(): string
    {
        return AlleleDeletionType::class;
    }

    public function __clone()
    {
        parent::__clone();
    }
}
