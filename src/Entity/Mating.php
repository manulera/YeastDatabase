<?php

namespace App\Entity;

use App\Form\MatingType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatingRepository")
 */
class Mating extends StrainSource
{

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", inversedBy="matings")
     */
    private $strains;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Strain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $strain2;

    public function __construct()
    {
        StrainSource::__construct();
        $this->formClass = MatingType::class;
        $this->name = "Mating Strains";
        $this->strains = new ArrayCollection();
    }

    /**
     * @return Collection|Strain[]
     */
    public function getStrains(): Collection
    {
        return $this->strains;
    }

    public function addStrain(Strain $strain): self
    {
        if (!$this->strains->contains($strain)) {
            $this->strains[] = $strain;
        }

        return $this;
    }

    public function removeStrain(Strain $strain): self
    {
        if ($this->strains->contains($strain)) {
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    public function getStrain1(): ?Strain
    {
        return $this->strain1;
    }

    public function setStrain1(?Strain $strain1): self
    {
        $this->strain1 = $strain1;

        return $this;
    }

    public function getStrain2(): ?Strain
    {
        return $this->strain2;
    }

    public function setStrain2(?Strain $strain2): self
    {
        $this->strain2 = $strain2;

        return $this;
    }

    public function createStrains(Form $form, array $options = [])
    {

        // dd($form->get("strain_choice")->getData());


        //     $nb_clones = $form->get("number_of_clones")->getData();
        //     $marker = $form->get("marker")->getData();
        //     for ($i = 0; $i < $nb_clones; $i++) {

        //         $new_allele = new Allele;
        //         $new_allele->setLocus($locus);
        //         $new_allele->setMarker($marker);
        //         $new_allele->setName($this->nameAllele($new_allele));
        //         $this->addAllele($new_allele);

        //         $new_strain = new Strain;
        //         $old_strain = $this->inputStrain;

        //         $new_strain->addAllele($new_allele);

        //         foreach ($old_strain->getAllele() as $old_allele) {
        //             if ($old_allele->getLocus() != $new_allele->getLocus()) {
        //                 $new_strain->addAllele($old_allele);
        //             }
        //         }
        //         $new_strain->updateGenotype();
        //         $this->addStrainsOut($new_strain);
        //     }
    }
}
