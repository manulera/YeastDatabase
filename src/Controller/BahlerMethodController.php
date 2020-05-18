<?php

namespace App\Controller;

use App\Entity\Oligo;
use App\Entity\Plasmid;
use App\Entity\Strain;
use App\Form\AlleleChunkyType;
use App\Form\AlleleDeletionType;
use App\Form\StrainPickerType;
use App\Service\Genotyper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/strain/new/molbiol/bahler", name="strain.source.molbiol.bahler.")
 */
class BahlerMethodController extends MolBiolController
{
    public function __construct(Genotyper $genotyper)
    {
        parent::__construct($genotyper);
        //TODO move this to strain_source
    }

    /**
     * @Route("/{option}", name="submethod")
     */
    public function subMethod(Request $request, string $option)
    {

        $form = $this->makeForm(['fields2show' => $option]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parentStrain = $form->get("inputStrain")->getData();
            $allele = $form->get("allele")->getData();
            $nb_clones = $form->get("numberOfClones")->getData();
            $primers = [];
            $primers[] = $form->get("primerForward")->getData();
            $primers[] = $form->get("primerReverse")->getData();
            $plasmids = [];
            $plasmids[] = $form->get("plasmid")->getData();
            return $this->persistMolBiol($parentStrain, $allele, $nb_clones, $plasmids, $primers);
        }
        if ($option == 'deletion') {
            $template_file = 'strain/source/bahler_deletion.html.twig';
        } else {
            $template_file = 'strain/source/bahler_chunky.html.twig';
        }
        // $template_file = fields2show

        return $this->render(
            $template_file,
            [
                'form' => $form->createView(),
            ]
        );
    }


    public function makeForm($options)
    {
        $oligoRepository = $this->getDoctrine()->getRepository(Oligo::class);
        $plasmidRepository = $this->getDoctrine()->getRepository(Plasmid::class);

        $builder = $this->createFormBuilder();
        $builder
            ->add('inputStrain', StrainPickerType::class, [
                'mapped' => false
            ])
            ->add('primerForward', EntityType::class, [
                'class' => Oligo::class,
                'mapped' => false,
                'choices' => $oligoRepository->findAll()
            ])
            ->add('primerReverse', EntityType::class, [
                'class' => Oligo::class,
                'mapped' => false,
                'choices' => $oligoRepository->findAll()
            ])
            ->add('plasmid', EntityType::class, [
                'class' => Plasmid::class,
                'mapped' => false,
                'choices' => $plasmidRepository->findAll()
            ]);

        if ($options['fields2show'] == "deletion") {
            $builder->add('allele', AlleleDeletionType::class, [
                'mapped' => false,
            ]);
        } else {
            $builder->add('allele', AlleleChunkyType::class, [
                'mapped' => false,
                'fields2show' => $options['fields2show']
            ]);
        }
        $builder->add('numberOfClones', IntegerType::class, [
            'mapped' => false,
        ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);

        return $builder->getForm();
    }
}
