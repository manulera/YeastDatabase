<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use App\Controller\StrainSourceController;
use App\Entity\StrainSource;
use App\Entity\Strain;
use App\Entity\StrainSourceTag;
use App\Service\Genotyper;
use App\Service\MatingForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;


/**
 * @Route("/strain/new/mating", name="strain.source.mating.")
 */
class MatingController extends StrainSourceController
{

    public function __construct(MatingForm $formBuilder, Genotyper $genotyper)
    {
        $this->formBuilder = $formBuilder;
        $this->genotyper = $genotyper;
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {

        // TODO this can be probably moved to the parent class
        $strain_source = new StrainSource;
        $form = $this->formBuilder->createForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->processStrains($form, $strain_source);
            return $this->persistStrainSource($strain_source);
        }
        return $this->render(
            'strain/source/mating.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    public function processStrains(Form $form, StrainSource $strain_source)
    {
        $data = [
            'strain1' => $form->get("strain1")->getData(),
            'strain2' => $form->get("strain2")->getData(),
            'strain_choice' => $form->get("strain_choice")->getData(),
        ];

        //Input strains
        $strain = $data['strain1'];
        // $repository = $this->getDoctrine()->getRepository(Strain::class);
        // $strain = $repository->find($strain);
        $strain_source->addStrainsIn($strain);

        $strain = $data['strain2'];
        $strain_source->addStrainsIn($strain);


        // Output strains
        $strainsOut = $data['strain_choice'];
        foreach ($strainsOut as $strain) {
            $strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($strain);
        }
        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'Mating']);
        $strain_source->addStrainSourceTag($tag);
    }


    /**
     * @Route("/combinations", name="combinations")
     */
    public function getStrainPair(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Strain::class);

        $strain1 = $repository->find($request->query->get('strain1'));
        $strain2 = $repository->find($request->query->get('strain2'));

        $options = [
            'strain1' => $strain1,
            'strain2' => $strain2
        ];
        // TODO set here the data of the form
        $form = $this->formBuilder->createForm($options);

        return $this->render('strain/source/mating_ajax.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
