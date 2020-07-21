<?php

namespace App\Controller;

use App\Entity\StrainSource;
use App\Entity\Strain;
use App\Entity\MolBiol;
use App\Entity\StrainSourceTag;
use App\Form\MarkerSwitchType;
use App\Form\MolBiolAlleleChunkyType;
use App\Form\MolBiolAlleleDeletionType;
use App\Service\Genotyper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\FormInterface;

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
            $template = "strain/source/bahler_deletion.html.twig";
            $form = $this->createForm(MolBiolAlleleDeletionType::class);
        } else {
            $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => $option]]);
            switch ($option) {
                case "cTag":
                    $template = "strain/source/bahler_cTag.html.twig";
                    break;
                case "nTag":
                    $template = "strain/source/bahler_nTag.html.twig";
                    break;
                case "promoter":
                    $template = "strain/source/bahler_promoter.html.twig";
                    break;
                case "promoter_nTag":
                    $template = "strain/source/bahler_promoter_nTag.html.twig";
                    break;
            }
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->persistMolBiol($form->getData(), $form->get("numberOfClones")->getData());
        }
        return $this->render(
            $template,
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/transformation", name="transformation")
     */
    public function transformationAction(Request $request)
    {
        $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => "all"]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            return $this->persistMolBiol($form->getData(), $form->get("numberOfClones")->getData());
        }
        return $this->render(
            'strain/source/transformation_custom.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/markerswitch/{strain}", name="markerswitch")
     * @Method("GET")
     */
    public function markerSwitchAction(Request $request, Strain $strain = null)
    {

        if ($strain !== null) {
            $ss = new StrainSource;
            $ss->addStrainsIn($strain);
            $form = $this->createForm(MarkerSwitchType::class, $ss);
        } else {
            $form = $this->createForm(MarkerSwitchType::class);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ss = $this->processMarkerSwitchForm($form);
            return $this->persistMarkerSwitch($ss, $form->get("numberOfClones")->getData());
        }

        return $this->render(
            'strain/source/marker_switch.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function processMarkerSwitchForm(Form $form)
    {

        $strain_source = $form->getData();
        $markerSwitchFormsChunky = $form->get('alleleChunkies')->getData();


        foreach ($markerSwitchFormsChunky as $this_form) {

            // Here we check both conditions because it could be that both markers have been changed.
            $n_changed = array_key_exists('nMarker', $this_form) && $this_form['nMarker'];
            $c_changed = array_key_exists('cMarker', $this_form) && $this_form['cMarker'];
            if ($n_changed || $c_changed) {
                $new_allele = clone $this_form['originalAllele'];
                if ($n_changed) {
                    $new_allele->setNMarker($this_form['nMarker']);
                }
                if ($c_changed) {
                    $new_allele->setCMarker($this_form['cMarker']);
                }
                $new_allele->setParentAllele($this_form['originalAllele']);
                $strain_source->addAllele($new_allele);
            }
        }
        // $allele_deletions = $form->get('alleleDeletions')->getData();
        // foreach ($allele_deletions as $allele_deletion) {
        //     $changed = array_key_exists('marker', $allele_deletion) && $allele_deletion['marker'];
        //     if ($changed) {
        //         $strain_source->addAllele($allele_deletion);
        //     }
        // }

        return $strain_source;
    }

    public function persistMarkerSwitch(StrainSource $strain_source, int $nb_clones)
    {
        // A few checks:
        dump($strain_source->getStrainsIn());
        if (count($strain_source->getStrainsIn()) != 1) {
            return "Input strains should be 1";
        }
        if (count($strain_source->getStrainsOut()) != 0) {
            return "Output strains should be empty";
        }
        if ($nb_clones === null || $nb_clones < 0) {
            return "Number of clones should be bigger than zero";
        }

        $new_alleles = $strain_source->getAlleles();

        foreach ($strain_source->getAlleles() as $allele) {
            $allele->updateName();
        }


        if ($nb_clones > 1) {
            for ($i = 1; $i < $nb_clones; $i++) {
                foreach ($new_alleles as $allele) {
                    $strain_source->addAllele(clone $allele);
                }
            }
        }
        dump($new_alleles);
        for ($i = 0; $i < $nb_clones; $i++) {
            $this_allele = $strain_source->getAlleles()[$i];
            $this_strain = clone $strain_source->getStrainsIn()[0];
            $this_strain->addAllele($this_allele);
            $this_strain->removeAllele($this_allele->getParentAllele());
            $this_strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($this_strain);
        }


        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'MarkerSwitch']);
        $strain_source->addStrainSourceTag($tag);
        return $this->persistStrainSource($strain_source);
    }

    public function persistMolBiol(StrainSource $strain_source, int $nb_clones)
    {
        // A few checks:
        if (count($strain_source->getStrainsIn()) != 1) {
            return "Input strains should be 1";
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
