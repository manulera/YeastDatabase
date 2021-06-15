<?php

namespace App\Controller;

use App\Entity\Oligo;
use App\Entity\Plasmid;
use App\Entity\Strain;

use App\Form\MolBiolAlleleChunkyType;
use App\Form\PointMutationType;
use App\Form\StrainPickerType;
use App\Form\TruncationType;
use App\Service\Genotyper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/strain/new/molbiol/custom_allele", name="strain.source.molbiol.custom_allele.")
 */
class CustomAlleleController extends MolBiolController
{
    public function __construct(Genotyper $genotyper)
    {
        parent::__construct($genotyper);
        //TODO move this to strain_source
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        
        $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => 'all']]);
        $form->handleRequest($request);
        // dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
            // return $this->render('strain/source/custom_allele.html.twig', [
            //     'form' => $form->createView(),
            // ]);
        }

        return $this->render('strain/source/custom_allele.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
