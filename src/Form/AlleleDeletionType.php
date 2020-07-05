<?php

namespace App\Form;

use App\Entity\AlleleDeletion;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlleleDeletionType extends AlleleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        switch ($options['fields2show']) {
            case "marker_switch":
                $builder->remove('locus');
                $builder->add('name', null, ['attr' => array('readonly' => true)]);
        }
        $builder
            ->add('marker');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AlleleDeletion::class,
            'fields2show' => null
        ]);
    }
}
