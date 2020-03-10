<?php

namespace App\Controller;

use App\Entity\CustomStrainSource;
use App\Entity\Strain;
use App\Form\CustomStrainSourceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\StrainSourceController;
use Symfony\Component\Form\Form;

/**
 * @Route("/strain/new/custom", name="strain.source.custom.")
 */
class CustomStrainSourceController extends StrainSourceController
{

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $strain_source = new CustomStrainSource;
        $form = $this->createForm(CustomStrainSourceType::class, $strain_source);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->processStrains($form, $strain_source);
            return $this->persistStrainSource($strain_source);
        }

        return $this->render(
            'strain/source/custom.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    public function processStrains(Form $form, CustomStrainSource $strain_source)
    {;
        $new_strain = new Strain;

        $new_strain->setMType($form->get('MatingType')->getData());
        $new_strain->updateGenotype($this->genotyper);
        $strain_source->addStrainsOut($new_strain);

        
    }
}
