<?php

namespace App\Form;

use App\Entity\CustomStrainSource;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomStrainSourceType extends StrainSourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomStrainSource::class,
        ]);
    }
}
