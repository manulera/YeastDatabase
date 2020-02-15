<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use App\Controller\StrainSourceController;
use App\Entity\Mating;
use App\Entity\Strain;
use App\Form\MatingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * @Route("/strain/create/mating", name="strain.source.mating.")
 */
class MatingController extends StrainSourceController
{

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {

        // TODO this can be probably moved to the parent class
        $strain_source = new Mating;
        $form = $this->createForm(MatingType::class, $strain_source);
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

    public function processStrains(Form $form, Mating $strain_source)
    {
        $strains = $form->get("strain_choice")->getData();

        foreach ($strains as $strain) {
            $strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($strain);
        }
    }


    /**
     * @Route("/combinations", name="combinations")
     */
    public function getStrainPair(Request $request)
    {
        $mating = new Mating();
        $repository = $this->getDoctrine()->getRepository(Strain::class);

        $strain1 = $repository->find($request->query->get('strain1'));
        $strain2 = $repository->find($request->query->get('strain2'));

        $mating->setStrain1($strain1);
        $mating->setStrain2($strain2);
        $form = $this->createForm(MatingType::class, $mating);

        return $this->render('strain/source/mating_ajax.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
