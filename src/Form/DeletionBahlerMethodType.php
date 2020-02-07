<?php

namespace App\Form;

use App\Entity\DeletionBahlerMethod;
use App\Entity\Locus;
use App\Entity\Marker;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('plasmid')
            ->add('locus', EntityType::class, [
                'class' => Locus::class,
                'mapped' => false
            ])
            ->add('marker', EntityType::class, [
                'class' => Marker::class,
                'mapped' => false
            ])
            ->add('number_of_clones', IntegerType::class, [
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
        ]);
    }
}
