<?php

namespace App\Form;

use App\Entity\Strain;
use App\Entity\Oligo;
use App\Entity\Plasmid;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BahlerMethodType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inputStrain', EntityType::class, [
                'class' => Strain::class,
                'mapped' => false
            ])
            ->add('primerForward', EntityType::class, [
                'class' => Oligo::class,
                'mapped' => false
            ])
            ->add('primerReverse', EntityType::class, [
                'class' => Oligo::class,
                'mapped' => false
            ])
            ->add('plasmid', EntityType::class, [
                'class' => Plasmid::class,
                'mapped' => false
            ]);

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
            'fields2show' => ''
        ]);
    }
}
