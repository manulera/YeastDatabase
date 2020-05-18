<?php

namespace App\Controller;

use App\Form\LocusPickerType;
use App\Form\StrainPickerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entitypicker", name="entitypicker.")
 */
class EntityPickerController extends AbstractController
{
    /**
     * @Route("/locus", name="locus")
     */
    public function ajaxPickLocus(Request $request)
    {
        $filter_locus_name = $request->query->get('filter_locus_name');
        $filter_pombase_id = $request->query->get('filter_pombase_id');

        $options = [
            'filterByLocusName' => $filter_locus_name,
            'filterByPombaseId' => $filter_pombase_id
        ];
        $form = $this->createForm(LocusPickerType::class, null, $options);

        return $this->render('forms/locus_picker.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/strain", name="strain")
     */
    public function ajaxPickStrain(Request $request)
    {
        $filter_strain_id = $request->query->get('filter_strain_id');
        $filter_genotype = $request->query->get('filter_genotype');

        $options = [
            'filterById' => $filter_strain_id,
            'filterByGenotype' => $filter_genotype
        ];
        $form = $this->createForm(StrainPickerType::class, null, $options);

        return $this->render('forms/strain_picker.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
