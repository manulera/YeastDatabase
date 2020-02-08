<?php

namespace App\Form;

use App\Entity\Mating;
use App\Entity\Strain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('strain1')
            ->add('strain2')
            ->add('strain_choice', EntityType::class, [
                'class' => Strain::class,
                'choice_value' => function (Strain $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'choices' => [],
                'mapped' => false,
                'multiple' => true,
                'expanded' => true
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Mating|null $data */
            $data = $event->getData();
            $formint = $event->getForm();
            if ($data->getStrain1() && $data->getStrain2()) {
                $out = $this->strainCombinations($data->getStrain1(), $data->getStrain2());
                $strains = $out["strains"];

                $formint->add('strain_choice', EntityType::class, [
                    'class' => Strain::class,
                    'choice_value' => function (Strain $entity = null) {
                        return $entity ? $entity->getId() : '';
                    },
                    'choices' => $strains,
                    'mapped' => false,
                    'multiple' => true,
                    'expanded' => true
                ]);
                $formint->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'dummy']);
                $formint->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary float-right',
                    ],
                ]);
            }
        });


        // //TODO show options only if strains are set and different
        // /** @var Mating|null $data */
        // $data = $options['data'] ?? null;



        // $builder->get('strain1')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

        //     $form = $event->getForm();
        //     /** @var Mating|null $data */
        //     $data = $form->getData();
        //     $this->addCombinations($form->getParent(), $data->getStrain1(), $data->getStrain2());
        // });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mating::class,
        ]);
    }

    // TODO move this somewhere
    function recursiveCombination($in_list, &$result_list, $depth, $current)
    {
        if ($depth == count($in_list)) {
            $result_list[] = $current;
            return;
        }
        for ($i = 0; $i < count($in_list[$depth]); $i++) {
            $temp =$current;
            $temp[] = $in_list[$depth][$i];
            recursiveCombination($in_list, $result_list, $depth + 1, $temp);
        }
    }



    public function strainCombinations(Strain $strain1, Strain $strain2)
    {
        // @var Collection|Allele[]
        $alleles1 = $strain1->getAllele();
        // @var Collection|Allele[]
        $alleles2 = $strain2->getAllele();

        $loci1 = [];
        $locus_allele = [];

        foreach ($alleles1 as $alle1) {
            /* @var $alle Allele */
            $loci1[$alle1->getLocus()->getName()] = $alle1;
        }

        foreach ($alleles2 as $alle2) {
            /* @var $alle Allele */
            $locus2_name = $alle2->getLocus()->getName();

            if (array_key_exists($locus2_name, $loci1)) {
                // We only include them if the alleles are different. In principle there would be no way to tell
                // the difference between the alleles!
                if ($loci1[$locus2_name] != $alle2) {
                    $locus_allele[$locus2_name] = [$loci1[$locus2_name], $alle2];
                } else {
                    $locus_allele[$locus2_name] = [$alle2];
                }
                unset($loci1[$locus2_name]);
            } else {
                $locus_allele[$locus2_name] = [null, $alle2];
            }
        }

        foreach ($loci1 as $locus => $alle1) {
            $locus_allele[$locus] = [$alle1, null];
        }

        $combination_number = 1;
        foreach ($locus_allele as $arr) {
            $combination_number *= count($arr);
        }
        // Create all the empty strains
        $strains = [];
        for ($i = 0; $i < $combination_number; $i++) {
            $strain = new Strain;
            // This id is temporary 
            $strain->setId($i);
            $strains[] = $strain;
        }

        // Previous version that would return an array of alleles instead of an array of
        // strains
        // $combinations = [];
        //TODO there must be a better way of doing this
        $loci = [];
        $combo = 1;
        foreach ($locus_allele as $locus => $allele) {

            for ($i = 0; $i < $combination_number; $i++) {
                // $combinations[$i][$locus] = $allele[intdiv($i & $combo, $combo)];

                $this_allele = $allele[intdiv($i & $combo, $combo)];

                if ($this_allele) {
                    $strains[$i]->addAllele($this_allele);
                }
            }
            $loci[] = $locus;
            $combo *= 2;
        }
        foreach ($strains as $strain) {
            $strain->updateGenotype();
        }
        $out = [];
        // $out['combinations'] = $combinations;
        $out['strains'] = $strains;
        $out['loci'] = $loci;
        return $out;
    }
}
