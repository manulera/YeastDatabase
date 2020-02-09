<?php

namespace App\Entity;

use App\Form\DeletionBahlerMethodType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Form\Form;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DeletionBahlerMethodRepository")
 */
class DeletionBahlerMethod extends MolBiol
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Oligo", inversedBy="deletionBahlerMethodsPrimerForward")
     * @JoinColumn(name="primerForward_name", referencedColumnName="name")
     */
    private $primerForward;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Oligo", inversedBy="deletionBahlerMethodsPrimerReverse")
     * @JoinColumn(name="primerReverse_name", referencedColumnName="name")
     */
    private $primerReverse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plasmid", inversedBy="deletionBahlerMethods")
     * @JoinColumn(name="plasmid_name", referencedColumnName="name")
     */
    private $plasmid;

    public function __construct()
    {
        MolBiol::__construct();
        $this->formClass = DeletionBahlerMethodType::class;
        $this->name = "Deletion Bahler Method";
    }

    public function getPrimerForward(): ?Oligo
    {
        return $this->primerForward;
    }

    public function setPrimerForward(?Oligo $primerForward): self
    {
        $this->primerForward = $primerForward;

        return $this;
    }

    public function getPrimerReverse(): ?Oligo
    {
        return $this->primerReverse;
    }

    public function setPrimerReverse(?Oligo $primerReverse): self
    {
        $this->primerReverse = $primerReverse;

        return $this;
    }

    public function getPlasmid(): ?Plasmid
    {
        return $this->plasmid;
    }

    public function setPlasmid(?Plasmid $plasmid): self
    {
        $this->plasmid = $plasmid;

        return $this;
    }

    private function nameAllele(Allele $allele)
    {
        $name = $allele->getLocus()->getName() . "Δ::" . $allele->getMarker();
        return $name;
    }

    public function createStrains(Form $form,array $options = [])
    {
        $locus = $form->get("locus")->getData();
        $nb_clones = $form->get("number_of_clones")->getData();
        $marker = $form->get("marker")->getData();
        for ($i = 0; $i < $nb_clones; $i++) {

            $new_allele = new Allele;
            $new_allele->setLocus($locus);
            $new_allele->setMarker($marker);
            $new_allele->setName($this->nameAllele($new_allele));
            $this->addAllele($new_allele);

            $new_strain = new Strain;
            $old_strain = $this->inputStrain;

            $new_strain->addAllele($new_allele);

            foreach ($old_strain->getAllele() as $old_allele) {
                if ($old_allele->getLocus() != $new_allele->getLocus()) {
                    $new_strain->addAllele($old_allele);
                }
            }
            $new_strain->updateGenotype();
            $this->addStrainsOut($new_strain);
        }
    }
}
