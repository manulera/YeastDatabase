<?php

namespace App\Form;


use App\Entity\AlleleDeletion;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            'entry_type' => AlleleType::class,
            'allow_add' => true,
        ]);
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $strainSource = $event->getData();
                $nb_strains_in = count($strainSource->getStrainsIn());

                if ($nb_strains_in == 1) {
                    $strain_in = $strainSource->getStrainsIn()[0];

                    $i = 0;
                    foreach ($strain_in->getAlleles() as $allele) {
                        if ($allele->hasMarker()) {
                            $i++;
                            $new_allele = clone $allele;
                            $new_allele->setParentAllele($allele);
                            $form->get('alleles')->add("allele$i", AlleleChunkyType::class, ['data' => $new_allele, 'fields2show' => 'marker_switch']);
                        }
                    }
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
