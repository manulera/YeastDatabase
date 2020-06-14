<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Entity\StrainSource;
use App\Entity\Strain;
use App\Entity\StrainSourceTag;
use App\Service\Genotyper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/strain/new/molbiol", name="strain.source.molbiol.")
 */
class MolBiolController extends StrainSourceController
{
    public function __construct(Genotyper $genotyper)
    {
        parent::__construct($genotyper);
        $this->controllerPossibilities = [
            'Bahler Method: Deletion' => 'bahler.deletion',
            'Bahler Method: Promoter Change' => 'bahler.promoter',
            'Bahler Method: Promoter Change + N-term tag' => 'bahler.promoter_nTag',
            'Bahler Method: C-term tag' => 'bahler.cTag',
            'Custom Allele' => 'custom_allele',
        ];
    }

    protected function redirect2Child(string $choice)
    {
        $arr = explode(".", $choice);
        if (count($arr) == 2) {
            return $this->redirectToRoute('strain.source.molbiol.' . $arr[0] . '.submethod', ["option" => $arr[1]]);
        } else {
            return $this->redirectToRoute('strain.source.molbiol.' . $choice . '.index');
        }
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    public function persistMolBiol(Strain $parent_strain, Allele $allele, int $nb_clones = 1, array $plasmids = [], array $oligos = [])
    {

        $strain_source = new StrainSource;
        $strain_source->addStrainsIn($parent_strain);

        $allele->updateName();

        // Add the resources
        foreach ($plasmids as $plasmid) {
            $strain_source->addPlasmid($plasmid);
        }
        foreach ($oligos as $oligo) {
            $strain_source->addOligo($oligo);
        }

        // Create the strains
        for ($i = 0; $i < $nb_clones; $i++) {

            $new_allele = clone $allele;
            $strain_source->addAllele($new_allele);

            $new_strain = new Strain;
            $old_strain = $parent_strain;
            $new_strain->setMType($old_strain->getMType());
            // TODO check if allele is in the same locus
            $new_strain->addAllele($new_allele);

            foreach ($old_strain->getAlleles() as $old_allele) {
                if ($old_allele->getLocus() != $new_allele->getLocus()) {
                    $new_strain->addAllele($old_allele);
                }
            }
            foreach ($old_strain->getPlasmids() as $plasmid) {
                $new_strain->addPlasmid($plasmid);
            }

            $new_strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($new_strain);
        }

        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'MolBiol']);
        $strain_source->addStrainSourceTag($tag);
        return $this->persistStrainSource($strain_source);
    }
}
