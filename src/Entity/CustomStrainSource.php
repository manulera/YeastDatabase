<?php

namespace App\Entity;

use App\Form\CustomStrainSourceType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;

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

    public function createStrains(Form $form, array $options = [])
    {
        $new_strain = new Strain;
        $new_strain->updateGenotype();
        $this->addStrainsOut($new_strain);
    }
}
