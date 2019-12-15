<?php

namespace App\Entity;

use App\Form\DeletionBahlerMethodType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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

    public function getId(): ?int
    {
        return $this->id;
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
}
