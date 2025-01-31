<?php

namespace App\Form;

use App\Entity\AlleleChunky;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MolBiolAlleleChunkyType extends MolBiolType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('allelesOut', CollectionType::class, [
            'entry_type' => AlleleChunkyType::class,
            'allow_add' => false,
            'required' => true,
            'entry_options' => $options['allele_options']
        ]);


        // The form will accept data, but if one of the collection fields is left empty,
        // it will create an empty field inside the collection.
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                if ($form->get('allelesOut')->getData() === null) {
                    $form->get('allelesOut')->setData([new AlleleChunky()]);
                }
            }

        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'allele_options' => null,
        ]);
    }
}
