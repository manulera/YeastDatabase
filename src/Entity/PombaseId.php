<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PombaseIdRepository")
 */
class PombaseId
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=20)
     */
    private $pombase_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Locus", mappedBy="pombase_id")
     */
    private $locus;

    public function __toString(): ?string
    {
        return $this->getPombaseId();
    }

    public function getPombaseId(): ?string
    {
        return $this->pombase_id;
    }

    public function setPombaseId(string $pombase_id): self
    {
        $this->pombase_id = $pombase_id;

        return $this;
    }

    public function getLocus(): ?Locus
    {
        return $this->locus;
    }

    public function setLocus(Locus $locus): self
    {
        $this->locus = $locus;

        // set the owning side of the relation if necessary
        if ($locus->getPombaseId() !== $this) {
            $locus->setPombaseId($this);
        }

        return $this;
    }
}
