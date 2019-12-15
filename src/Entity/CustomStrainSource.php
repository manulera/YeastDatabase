<?php

namespace App\Entity;

use App\Form\CustomStrainSourceType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomStrainSourceRepository")
 */
class CustomStrainSource extends StrainSource
{

    public function __construct()
    {
        StrainSource::__construct();
        $this->formClass = CustomStrainSourceType::class;
        $this->name = "Custom strain creation";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function createStrains()
    {
        $new_strain = new Strain;
        $this->addStrainsOut($new_strain);
    }
}
