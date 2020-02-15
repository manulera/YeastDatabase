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
use App\Controller\StrainSourceController;

/**
 * @Route("/strain/create/deletion_bahler", name="strain.source.deletion_bahler.")
 */
class DeletionBahlerMethodController extends StrainSourceController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        // TODO this can be probably moved to the parent class
        $strain_source = new DeletionBahlerMethod;
        $form = $this->createForm(DeletionBahlerMethodType::class, $strain_source);
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
        // @var App\Entity\Locus
        $locus = $form->get("locus")->getData();
        $nb_clones = $form->get("number_of_clones")->getData();
        $marker = $form->get("marker")->getData();
        for ($i = 0; $i < $nb_clones; $i++) {

            $new_allele = new Allele;
            $new_allele->setLocus($locus);
            $new_allele->setMarker($marker);
            $new_allele->setName($strain_source->nameAllele($new_allele));
            $strain_source->addAllele($new_allele);

            $new_strain = new Strain;
            $old_strain = $strain_source->getInputStrain();

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
