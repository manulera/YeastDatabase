<?php

namespace App\Controller;

use App\Entity\Oligo;
use App\Entity\Plasmid;
use App\Entity\Strain;
use App\Form\AlleleChunkyType;
use App\Form\PointMutationType;
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
        $form = $this->makeForm();
        $form->handleRequest($request);
        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {

            dump($form->get('inputPlasmids')->getData());

            // return $this->render('strain/source/custom_allele.html.twig', [
            //     'form' => $form->createView(),
            // ]);
        }

        return $this->render('strain/source/custom_allele.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change_form", name="change_form")
     */
    public function changeForm(Request $request)
    {
        $button_id = $request->query->get('button_id');
        dd($button_id);
    }

    public function makeForm($options = [])
    {
        $oligoRepository = $this->getDoctrine()->getRepository(Oligo::class);
        $plasmidRepository = $this->getDoctrine()->getRepository(Plasmid::class);

        $builder = $this->createFormBuilder();
        $builder
            ->add('inputStrain', EntityType::class, [
                'class' => Strain::class,
                'mapped' => false
            ])
            ->add('number_of_clones', IntegerType::class, [
                'mapped' => false,
            ])
            ->add('allele', AlleleChunkyType::class, [
                'mapped' => false,
                'fields2show' => 'all'
            ])
            ->add(
                'pointMutations',
                CollectionType::class,
                [
                    'entry_type' => PointMutationType::class,
                    'mapped' => false,
                    'allow_add' => true
                ]
            )
            ->add(
                'truncations',
                CollectionType::class,
                [
                    'entry_type' => TruncationType::class,
                    'mapped' => false,
                    'allow_add' => true
                ]
            )
            ->add('inputPlasmids', EntityType::class, [
                'class' => Plasmid::class,
                'mapped' => false,
                'multiple' => true,
                'attr' => ['class' => 'bootstrap-multiple-target'],
                'choices' => $plasmidRepository->findAll()
            ])
            ->add('inputOligos', EntityType::class, [
                'class' => Oligo::class,
                'mapped' => false,
                'multiple' => true,
                'attr' => ['class' => 'bootstrap-multiple-target'],
                'choices' => $oligoRepository->findAll()
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);

        return $builder->getForm();
    }
}
