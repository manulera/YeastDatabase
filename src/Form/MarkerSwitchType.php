<?php

namespace App\Form;


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
        $builder->add('alleles', CollectionType::class, [
            'allow_add' => true,
        ]);

        $formModifier = function (FormEvent $event, $strainSource) {

            if ($strainSource === null) {
                dump("a");
                return;
            }
            $form = $event->getForm();
            $nb_strains_in = count($strainSource->getStrainsIn());
            if ($nb_strains_in == 1) {
                $strain_in = $strainSource->getStrainsIn()[0];
                $allele_data = [];

                foreach ($strain_in->getAlleles() as $allele) {
                    if ($allele->hasMarker()) {
                        $new_allele = clone $allele;
                        $new_allele->setParentAllele($allele);
                        //THE PROBLEM IS HERE!!!!!!
                        $form->get('alleles')->add($i, $allele->getAssociatedForm(), ['data' => $new_allele, 'fields2show' => 'marker_switch']);
                    }
                }
            }
        };


        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {

                $formModifier($event, $event->getData());
            }
        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $formModifier($event, $event->getForm()->getData());
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
