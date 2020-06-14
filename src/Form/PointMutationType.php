<?php

namespace App\Form;

use App\Entity\PointMutation;
use App\Service\SequenceService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'disabled' => true
            ])
            ->add('newAminoAcid', ChoiceType::class, [
                'choices' => $this->sequence->getAminoAcids(),
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
            ])
            ->add('originalCodon', TextType::class, [
                'disabled' => true
            ])
            ->add('newCodon');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PointMutation::class,
        ]);
    }
}
