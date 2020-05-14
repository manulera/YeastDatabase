<?php

namespace App\Service;

use App\Entity\Strain;
use App\Service\Genotyper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;


class MatingForm
{

    public function __construct(ManagerRegistry $doctrine, Genotyper $genotyper, FormFactoryInterface $formFactory)
    {
        $this->doctrine = $doctrine;
        $this->genotyper = $genotyper;
        $this->formFactory = $formFactory;
        $this->builder = $formFactory->createBuilder();
    }

    public function createForm(array $options = ['strain1' => null, 'strain2' => null])
    {

        $strainRepository = $this->doctrine->getRepository(Strain::class);

        $this->builder
            ->add('strain1', EntityType::class, [
                'class' => Strain::class,
                'mapped' => false,
                'data' => $options['strain1'],


            ])

            ->add('strain2', EntityType::class, [
                'class' => Strain::class,
                'mapped' => false,
                'data' => $options['strain2'],

            ]);


        $formModifier = function (Form $form, array $options) {
            $form
                ->add('strain_choice', ChoiceType::class, [
                    'mapped' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => $options
                ]);
            if (count($options)) {
                $form->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary float-right',
                    ]
                ]);
            }
        };
        
        $this->builder->addEventListener(
            FormEvents::POST_SET_DATA,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $options = [];
                $strain1 = $form->get('strain1')->getData();
                $strain2 = $form->get('strain2')->getData();
                if ($strain1 && $strain2) {
                    $options = $this->strainCombinations($strain1, $strain2);
                }
                $formModifier($form, $options);
            }
        );

        $this->builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $data =   $event->getData();
                $form = $event->getForm();
                $modifier_options = [];
                $strain1 = $data['strain1'];
                $strain2 = $data['strain2'];
                $strain_repository = $this->doctrine->getRepository(Strain::class);
                $strain1 = $strain_repository->find($strain1);
                $strain2 = $strain_repository->find($strain2);
                if ($strain1 && $strain2) {
                    $modifier_options = $this->strainCombinations($strain1, $strain2);
                }
                $formModifier($form, $modifier_options);
            }
        );
        return $this->builder->getForm();
    }

    private function recursiveCombination($in_list, &$result_list, $depth, $current)
    {
        if ($depth == count($in_list)) {
            $result_list[] = $current;
            return;
        }
        for ($i = 0; $i < count($in_list[$depth]); $i++) {
            $temp = $current;
            $temp[] = $in_list[$depth][$i];
            $this->recursiveCombination($in_list, $result_list, $depth + 1, $temp);
        }
    }


    // From two lists of alleles generate an array with a position for each locus
    // at each position there is an array with the possible alleles for each locus

    private function getLocusAllele($alleles1, $alleles2)
    {
        // Output array
        $alleles = [];

        // Store the loci1=>allele1 found in $alleles1
        $loci1 = [];
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
                // TODO handle the case in which the allele names are the same but not the id
                if ($loci1[$locus2_name] != $alle2) {
                    $alleles[] = [$loci1[$locus2_name], $alle2];
                } else {
                    $alleles[] = [$alle2];
                }
                // We pop the value
                unset($loci1[$locus2_name]);
            } else {
                $alleles[] = [null, $alle2];
            }
        }
        // These are the loci that were present in the first list, but not the second
        foreach ($loci1 as $locus => $alle1) {
            $alleles[] = [$alle1, null];
        }

        return $alleles;
    }

    private function combineMatingTypes(array $strain_possibilities, string $mating1, string $mating2)
    {

        if ($mating1 == $mating2) {
            foreach ($strain_possibilities as $strain) {
                $strain->setMType($mating1);
            }
            return $strain_possibilities;
        } else {
            $out = [];
            foreach ($strain_possibilities as $strain) {
                $copy = clone $strain;
                $strain->setMType($mating1);
                $out[] = $strain;
                $copy->setMType($mating2);
                $out[] = $copy;
            }
            return $out;
        }
    }
    // Combinations of an array elements
    // from https://www.oreilly.com/library/view/php-cookbook/1565926811/ch04s25.html

    function combinationsOneArray($array)
    {
        // initialize by adding the empty set
        $results = array(array());

        foreach ($array as $element)
            foreach ($results as $combination)
                array_push($results, array_merge(array($element), $combination));

        return $results;
    }

    private function combinePlasmids(array $strain_possibilities, array $plasmids)
    {
        if (!count($plasmids)) {
            return $strain_possibilities;
        }

        $plasmid_combinations = $this->combinationsOneArray($plasmids);

        $out = [];

        foreach ($plasmid_combinations as $plasmid_combination) {
            foreach ($strain_possibilities as $strain) {
                $out[] = clone $strain;

                foreach ($plasmid_combination as $plasmid) {
                    end($out)->addPlasmid($plasmid);
                }
            }
        }

        return $out;
    }

    private function strainCombinations(Strain $strain1, Strain $strain2)
    {
        if ($strain1 == $strain2) {
            return [];
        }
        if (($strain1->getMType() == $strain2->getMType()) && $strain1->getMType() != "h90") {
            return [];
        }

        $locus_alleles = $this->getLocusAllele($strain1->getAlleles(), $strain2->getAlleles());

        $strain_possibilities = array();
        $this->recursiveStrainCombination($locus_alleles, $strain_possibilities, 0, array());

        $plasmids = array_unique(array_merge($strain1->getPlasmids()->toArray(), $strain2->getPlasmids()->toArray()));

        $strain_possibilities = $this->combinePlasmids($strain_possibilities, $plasmids);
        $strain_possibilities = $this->combineMatingTypes($strain_possibilities, $strain1->getMType(), $strain2->getMType());
        // We make an array where the keys are the genotypes and the values are the lists
        // array ids
        $options = array();

        foreach ($strain_possibilities as $strain) {
            $strain->updateGenotype($this->genotyper);
            $genotype = $strain->getGenotype();
            if (!$genotype) {
                $genotype = ' ';
            }
            $options[$genotype] = $strain;
        }
        return $options;
    }

    private  function recursiveStrainCombination($in_list, &$result_list, $depth, $current)
    {
        if ($depth == count($in_list)) {
            $st = new Strain;
            foreach ($current as $alle) {
                if ($alle) {
                    $st->addAllele($alle);
                }
            }
            $result_list[] = $st;
            return;
        }
        for ($i = 0; $i < count($in_list[$depth]); $i++) {
            $temp = $current;
            $temp[] = $in_list[$depth][$i];
            $this->recursiveStrainCombination($in_list, $result_list, $depth + 1, $temp);
        }
    }
}
