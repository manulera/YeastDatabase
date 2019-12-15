<?php

namespace App\Form;

use App\Entity\DeletionBahlerMethod;
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
