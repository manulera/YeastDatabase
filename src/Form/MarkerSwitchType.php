<?php

namespace App\Form;

use App\Entity\AlleleChunky;
use App\Entity\AlleleDeletion;
use App\Entity\StrainSource;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkerSwitchType extends MolBiolType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('alleleChunkies', CollectionType::class, [
            'allow_add' => true,
            'entry_type' => MarkerSwitchAlleleChunkyType::class,
            'mapped' => false,
        ]);

        $formModifier = function (FormEvent $event, $strainSource) {

            if ($strainSource === null) {
                return;
            }
            $form = $event->getForm();
            $nb_strains_in = count($strainSource->getStrainsIn());
            if ($nb_strains_in == 1) {
                $strain_in = $strainSource->getStrainsIn()[0];
                $allele_chunky = [];
                $allele_deletion = [];
                foreach ($strain_in->getAlleles() as $allele) {
                    if ($allele->hasMarker()) {
                        if (get_class($allele) == AlleleChunky::class) {
                            $allele_chunky[] = ['originalAllele' => $allele];
                        }
                    }
                }
                $form->get('alleleChunkies')->setData($allele_chunky);
            }
        };


        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                dump($event->getData());
                $formModifier($event, $event->getData());
            }
        );

        // $builder->addEventListener(
        //     FormEvents::PRE_SUBMIT,
        //     function (FormEvent $event) use ($formModifier) {

        //         $formModifier($event, $event->getForm()->getData());
        //     }
        // );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'allele_options' => null,
        ]);
    }
}
