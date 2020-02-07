<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MolBiolRepository")
 */
abstract class MolBiol extends StrainSource
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain", inversedBy="molBiolInput")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $inputStrain;

    public function __construct()
    {
        StrainSource::__construct();
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
