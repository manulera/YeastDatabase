<?php

namespace App\Form;

use App\Entity\Mating;
use App\Entity\Strain;
use App\Repository\StrainRepository;
use App\Service\Genotyper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MatingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('strain1')
            ->add('strain2');

        $formModifier = function (Form $form, array $options) {
            $form
                ->add('strain_choice', ChoiceType::class, [
                    'mapped' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => $options,
                    'attr' => ['display' => false]
                ]);
            if (count($options)) {
                $form->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary float-right',
                    ]
                ]);
            }
        };

        // TODO this could be made an event listener PRE_SET_DATA I just dont
        // see it makes it clearer
        // if (count($genotype_index)) {
        //     $builder
        //         ->add('save', SubmitType::class, [
        //             'attr' => [
        //                 'class' => 'btn btn-primary float-right',
        //             ]
        //         ]);
        // }

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $data = $form->getData();
                $options = [];
                if ($data->getStrain1() && $data->getStrain2()) {
                    $options = $this->strainCombinations2($data->getStrain1(), $data->getStrain2());
                }
                $formModifier($form, $options);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $options = [];
                $data = $event->getData();
                $strain1 = $this->strain_repository->find($data['strain1']);
                $strain2 = $this->strain_repository->find($data['strain2']);
                if ($strain1 && $strain2) {
                    $options = $this->strainCombinations2($strain1, $strain2);
                }
                $formModifier($form, $options);
            }
        );
    }

    public function recursiveCombination($in_list, &$result_list, $depth, $current)
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

    public function getLocusAllele($alleles1, $alleles2)
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

    public function strainCombinations2(Strain $strain1, Strain $strain2)
    {
        if ($strain1 == $strain2) {
            return [];
        }

        $locus_alleles = $this->getLocusAllele($strain1->getAllele(), $strain2->getAllele());

        $strain_possibilities = array();
        $this->recursiveStrainCombination($locus_alleles, $strain_possibilities, 0, array());

        // We make an array where the keys are the genotypes and the values are the lists
        // array ids

        $options = array();

        foreach ($strain_possibilities as $strain) {
            $genotype = $this->genotyper->getGenotype($strain->getAllele());
            if (!$genotype) {
                $genotype = ' ';
            }
            $options[$genotype] = $strain;
        }
        return $options;
    }

    public function strainCombinations(Strain $strain1, Strain $strain2)
    {
        if ($strain1 == $strain2) {
            return [];
        }

        $locus_alleles = $this->getLocusAllele($strain1->getAllele(), $strain2->getAllele());

        $allele_combinations = array();
        $this->recursiveCombination($locus_alleles, $allele_combinations, 0, array());

        // We make an array where the keys are the genotypes and the values are the lists
        // array ids

        $genotype_alleleids = array();

        foreach ($allele_combinations as $allele_combo) {
            $genotype = $this->genotyper->getGenotype($allele_combo);
            $genotype_alleleids[$genotype] = array();
            foreach ($allele_combo as $allele) {
                // Wt allele is a null, and should not be added
                if ($allele) {
                    $genotype_alleleids[$genotype][] = $allele->getId();
                }
            }
        }
        return $genotype_alleleids;
    }
    public function recursiveStrainCombination($in_list, &$result_list, $depth, $current)
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




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mating::class,
        ]);
    }

    /**
     * @var Genotyper
     * An array of strings containing the controllers we will redirect to
     */
    private $genotyper;

    /**
     * @var StrainRepository
     * An array of strings containing the controllers we will redirect to
     */
    private $strain_repository;

    public function __construct(Genotyper $genotyper, StrainRepository $strainRepository)
    {
        $this->genotyper = $genotyper;
        $this->strain_repository = $strainRepository;
    }
}
