<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MolBiolRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="molBiolType", type="string")
 * @ORM\DiscriminatorMap({
 *      "deletionBahlerMethod" = "DeletionBahlerMethod"
 * })
 */
abstract class MolBiol extends StrainSource
{

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $molBiolType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="molBiolInput")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inputStrain;

    public function __construct()
    {
        StrainSource::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMolBiolType(): ?string
    {
        return $this->molBiolType;
    }

    public function setMolBiolType(string $molBiolType): self
    {
        $this->molBiolType = $molBiolType;

        return $this;
    }
}
