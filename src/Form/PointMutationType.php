<?php

namespace App\Form;

use App\Entity\PointMutation;
use App\Service\SequenceService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointMutationType extends AbstractType
{

    private $sequence;

    public function __construct(SequenceService $sequence)
    {
        $this->sequence = $sequence;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO take allele as possible input
        $builder
            ->add('sequencePosition')
            ->add('originalAminoAcid', TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('newAminoAcid', ChoiceType::class, [
                'choices' => $this->sequence->getAminoAcids(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
            ])
            ->add('originalCodon', TextType::class, [
                'attr' => array('readonly' => true)
            ])
            ->add('newCodon', ChoiceType::class, [
                'choices' => ["unknown"],
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                }
            ]);
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $new_aa = $event->getData()['newAminoAcid'];
                dump($new_aa);
                $possible_codons = array_merge($this->sequence->getCodonsFromAminoAcid($new_aa), ['unknown']);
                $event->getForm()->add(
                    'newCodon',
                    ChoiceType::class,
                    [
                        'choices' => $possible_codons,
                        'choice_label' => function ($choice, $key, $value) {
                            return $value;
                        }
                    ]
                );
            }
        );
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        //Todo validation of codon before submission
        $resolver->setDefaults([
            'data_class' => PointMutation::class,
        ]);
    }
}
