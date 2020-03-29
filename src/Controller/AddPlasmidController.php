<?php

namespace App\Controller;

use App\Entity\Plasmid;
use App\Entity\Strain;
use App\Entity\StrainSource;
use App\Entity\StrainSourceTag;
use App\Service\Genotyper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/strain/new/plasmid", name="strain.source.plasmid.")
 */
class AddPlasmidController extends StrainSourceController
{
    public function __construct(Genotyper $genotyper)
    {
        parent::__construct($genotyper);
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $form = $this->makeForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $parent_strain = $form->get("inputStrain")->getData();
            $nb_clones = $form->get("number_of_clones")->getData();
            $plasmid = $form->get("plasmid")->getData();
            return $this->persistAddPlasmid($parent_strain, $plasmid, $nb_clones);
        }
        return $this->render('strain/source/plasmid.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function persistAddPlasmid(Strain $parent_strain, Plasmid $plasmid, int $nb_clones)
    {
        $strain_source = new StrainSource;

        $strain_source->addStrainsIn($parent_strain);
        $strain_source->addPlasmid($plasmid);

        for ($i = 0; $i < $nb_clones; $i++) {
            $new_strain = new Strain;
            $new_strain->addPlasmid($plasmid);
            $new_strain->setMType($parent_strain->getMType());

            foreach ($parent_strain->getAlleles() as $old_allele) {
                $new_strain->addAllele($old_allele);
            }
            foreach ($parent_strain->getPlasmids() as $old_plasmid) {
                $new_strain->addPlasmid($old_plasmid);
            }
            $new_strain->updateGenotype($this->genotyper);
            $strain_source->addStrainsOut($new_strain);
        }

        $tag = $this->getDoctrine()->getRepository(StrainSourceTag::class)->findOneBy(['name' => 'Plasmid']);
        $strain_source->addStrainSourceTag($tag);
        return $this->persistStrainSource($strain_source);
    }
    
    public function makeForm($options = []): FormInterface
    {
        $strainRepository = $this->getDoctrine()->getRepository(Strain::class);
        $plasmidRepository = $this->getDoctrine()->getRepository(Plasmid::class);
        $builder = $this->createFormBuilder();
        $builder
            ->add('inputStrain', EntityType::class, [
                'class' => Strain::class,
                'mapped' => false,
                'choices' => $strainRepository->findAll()
            ])
            ->add('plasmid', EntityType::class, [
                'class' => Plasmid::class,
                'mapped' => false,
                'choices' => $plasmidRepository->findAll()
            ])
            ->add('number_of_clones', IntegerType::class, [
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
