<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Entity\StrainSource;
use App\Entity\Strain;
use App\Entity\StrainSourceTag;
use App\Form\AlleleChunkyType;
use App\Form\AlleleDeletionType;
use App\Form\MarkerSwitchType;
use App\Form\MolBiolAlleleChunkyType;
use App\Form\MolBiolAlleleDeletionType;
use App\Form\StrainSourceType;
use App\Service\Genotyper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

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
            'Marker Switch' => 'markerswitch',
            'Custom Transformation' => 'transformation',

        ];
    }

    protected function redirect2Child(string $choice)
    {
        $arr = explode(".", $choice);
        if (count($arr) == 2) {
            return $this->redirectToRoute('strain.source.molbiol.' . $arr[0], ["option" => $arr[1]]);
        } else {
            return $this->redirectToRoute('strain.source.molbiol.' . $choice);
        }
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * @Route("/bahler/{option}", name="bahler")
     */
    public function bahlerMethodAction(Request $request, string $option)
    {

        if ($option == 'deletion') {
            $allele_form_template = "forms/allele/allele_deletion_type.html.twig";
            $form = $this->createForm(MolBiolAlleleDeletionType::class);
        } else {
            $allele_form_template = "forms/allele/allele_chunky_type.html.twig";
            $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => $option]]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->persistMolBiol($form->getData(), $form->get("numberOfClones")->getData());
        }
        return $this->render(
            'strain/source/molbiol.html.twig',
            [
                'form' => $form->createView(),
                'allele_form_template' => $allele_form_template
            ]
        );
    }

    /**
     * @Route("/transformation", name="transformation")
     */
    public function transformationAction(Request $request)
    {
        $allele_form_template = "forms/allele/allele_chunky_type.html.twig";
        $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => "all"]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
            return $this->persistMolBiol($form->getData(), $form->get("numberOfClones")->getData());
        }
        return $this->render(
            'strain/source/molbiol.html.twig',
            [
                'form' => $form->createView(),
                'allele_form_template' => $allele_form_template
            ]
        );
    }

    /**
     * @Route("/markerswitch", name="markerswitch")
     */
    public function markerSwitchAction(Request $request)
    {
        $strain = $this->getDoctrine()->getRepository(Strain::class)->find(35);
        $ss = new StrainSource;
        $ss->addStrainsIn($strain);
        $form = $this->createForm(MarkerSwitchType::class, $ss);
        $form->handleRequest($request);

        return $this->render(
            'forms/allele/marker_switch.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function persistMolBiol(StrainSource $strain_source, int $nb_clones)
    {
        // A few checks:
        if (count($strain_source->getStrainsIn()) != 1) {
            return "Input strains should be 1";
        }
        if (count($strain_source->getAlleles()) != 1) {
            return "Input alleles should be 1";
        }
        if (count($strain_source->getStrainsOut()) != 0) {
            return "Output strains should be empty";
        }
        if ($nb_clones === null || $nb_clones < 0) {
            return "Number of clones should be bigger than zero";
        }

        // If more than one clone has been generated, the alleles are different even though
        // their genotype is the same

        $strain_source->getAlleles()[0]->updateName();

        if ($nb_clones > 1) {
            for ($i = 1; $i < $nb_clones; $i++) {
                $strain_source->addAllele(clone $strain_source->getAlleles()[0]);
            }
        }

        for ($i = 0; $i < $nb_clones; $i++) {
            $this_allele = $strain_source->getAlleles()[$i];
            $this_strain = clone $strain_source->getStrainsIn()[0];
            $this_strain->addAllele($this_allele);
            $this_strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($this_strain);
        }

        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'MolBiol']);
        $strain_source->addStrainSourceTag($tag);
        return $this->persistStrainSource($strain_source);
    }



    // public function persistMolBiol(Strain $parent_strain, Allele $allele, int $nb_clones = 1, array $plasmids = [], array $oligos = [])
    // {

    //     $strain_source = new StrainSource;
    //     $strain_source->addStrainsIn($parent_strain);

    //     $allele->updateName();

    //     // Add the resources
    //     foreach ($plasmids as $plasmid) {
    //         $strain_source->addPlasmid($plasmid);
    //     }
    //     foreach ($oligos as $oligo) {
    //         $strain_source->addOligo($oligo);
    //     }

    //     // Create the strains
    //     for ($i = 0; $i < $nb_clones; $i++) {

    //         $new_allele = clone $allele;
    //         $strain_source->addAllele($new_allele);

    //         $new_strain = new Strain;
    //         $old_strain = $parent_strain;
    //         $new_strain->setMType($old_strain->getMType());
    //         // TODO check if allele is in the same locus
    //         $new_strain->addAllele($new_allele);

    //         foreach ($old_strain->getAlleles() as $old_allele) {
    //             if ($old_allele->getLocus() != $new_allele->getLocus()) {
    //                 $new_strain->addAllele($old_allele);
    //             }
    //         }
    //         foreach ($old_strain->getPlasmids() as $plasmid) {
    //             $new_strain->addPlasmid($plasmid);
    //         }

    //         $new_strain->updateGenotype($this->genotyper);
    //         $strain_source->addStrainsOut($new_strain);
    //     }

    //     $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'MolBiol']);
    //     $strain_source->addStrainSourceTag($tag);
    //     return $this->persistStrainSource($strain_source);
    // }
}
