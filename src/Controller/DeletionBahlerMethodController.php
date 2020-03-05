<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Entity\DeletionBahlerMethod;
use App\Entity\Locus;
use App\Entity\Strain;
use App\Form\DeletionBahlerMethodType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/strain/new/molbiol/bahler", name="strain.source.molbiol.bahler.")
 */
class DeletionBahlerMethodController extends MolBiolController
{
    /**
     * @Route("/{option}", name="new")
     */
    public function newAction(string $option, Request $request)
    {
        // TODO this can be probably moved to the parent class
        $strain_source = new DeletionBahlerMethod;
        $form = $this->createForm(DeletionBahlerMethodType::class, $strain_source, ['fields2show' => $option]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->processStrains($form, $strain_source);
            return $this->persistStrainSource($strain_source);
        }
        return $this->render(
            'strain/source/bahler_deletion.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    public function processStrains(Form $form, DeletionBahlerMethod $strain_source)
    {
        //TODO move this up to molbiol


        $allele = $form->get("allele")->getData();
        $allele->updateName();
        $nb_clones = $form->get("number_of_clones")->getData();

        for ($i = 0; $i < $nb_clones; $i++) {

            $new_allele = clone $allele;
            $strain_source->addAllele($new_allele);

            $new_strain = new Strain;
            $old_strain = $strain_source->getInputStrain();
            // TODO check if allele is in the same locus
            $new_strain->addAllele($new_allele);

            foreach ($old_strain->getAllele() as $old_allele) {
                if ($old_allele->getLocus() != $new_allele->getLocus()) {
                    $new_strain->addAllele($old_allele);
                }
            }
            $new_strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($new_strain);
        }
    }
}
