<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MolBiolRepository")
 */
class MolBiol
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="molBiolInput")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inputStrain;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getInputStrain(): ?Strain
    {
        return $this->inputStrain;
    }

    public function setInputStrain(?Strain $inputStrain): self
    {
        $this->inputStrain = $inputStrain;

        return $this;
    }
}
