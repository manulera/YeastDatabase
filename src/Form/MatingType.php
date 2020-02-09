<?php

namespace App\Form;

use App\Entity\Mating;
use App\Entity\Strain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('strain_choice', ChoiceType::class, [
                'choices' => $options["genotype_index"],
                'mapped' => false,
                // 'by_reference' => false,
                // 'expanded' => true
            ])
            ->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'none']);

        if (count($options["genotype_index"])) {

            $builder
                ->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'dummy'])
                ->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary float-right',
                    ]
                ]);
        }


        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     /** @var Mating|null $data */

        //     $formint = $event->getForm();
        //     $formint->op

        //     if (count($possible_strains)) {

        //         $formint->add('strain_choice', EntityType::class, [
        //             'class' => Strain::class,
        //             'choices' => $possible_strains,
        //             'mapped' => false,
        //             'by_reference' => true,

        //             // 'multiple' => true,
        //             // 'expanded' => true
        //         ]);
        //         $formint->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'dummy']);
        //         $formint->add('save', SubmitType::class, [
        //             'attr' => [
        //                 'class' => 'btn btn-primary float-right',
        //             ],
        //         ]);
        //     }
        // });


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
            'genotype_index' => [],
        ]);
    }
}
