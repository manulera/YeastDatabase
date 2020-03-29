<?php

namespace App\Controller;

use App\Entity\StrainSource;
use App\Entity\Strain;
use App\Form\CustomStrainSourceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\StrainSourceController;
use App\Entity\StrainSourceTag;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $strain_source = new StrainSource;
        $form = $this->makeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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

    public function processStrains(Form $form, StrainSource $strain_source)
    {;
        $new_strain = new Strain;

        $new_strain->setMType($form->get('MatingType')->getData());
        $new_strain->updateGenotype($this->genotyper);
        $strain_source->addStrainsOut($new_strain);
        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'Custom']);
        $strain_source->addStrainSourceTag($tag);
    }

    public function makeForm($options = [])
    {

        $builder = $this->createFormBuilder();
        $builder
            ->add('MatingType', ChoiceType::class, [
                'choices' => ['h+', 'h-', 'h90'],
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
                'mapped' => false
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
        return $builder->getForm();
    }
}
