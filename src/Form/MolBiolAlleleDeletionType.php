<?php

namespace App\Form;

use App\Entity\AlleleChunky;
use App\Entity\AlleleDeletion;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MolBiolAlleleDeletionType extends MolBiolType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('alleles', CollectionType::class, [
            'entry_type' => AlleleDeletionType::class,
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
                if ($form->get('alleles')->getData() === null) {
                    $form->get('alleles')->setData([new AlleleDeletion()]);
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
