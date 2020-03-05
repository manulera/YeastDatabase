<?php

namespace App\Form;

use App\Entity\DeletionBahlerMethod;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DeletionBahlerMethodType extends StrainSourceType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inputStrain')
            ->add('primerForward')
            ->add('primerReverse')
            ->add('plasmid');

        if ($options['fields2show'] == "deletion") {
            $builder->add('allele', AlleleDeletionType::class, [
                'mapped' => false,
            ]);
        } else {
            $builder->add('allele', AlleleChunkyType::class, [
                'mapped' => false,
                'fields2show' => $options['fields2show']
            ]);
        }
        $builder->add('number_of_clones', IntegerType::class, [
            'mapped' => false,
        ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeletionBahlerMethod::class,
            'fields2show' => ''
        ]);
    }
}
