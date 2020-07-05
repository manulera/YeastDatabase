<?php

namespace App\Form;

use App\Entity\Plasmid;
use App\Entity\Oligo;
use App\Entity\Strain;
use App\Repository\OligoRepository;
use App\Repository\PlasmidRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MolBiolType extends StrainSourceType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
        $builder
            ->add('plasmids', EntityType::class, [
                'class' => Plasmid::class,
                'multiple' => true,
                'query_builder' => function (PlasmidRepository $plasmidRepository) {
                    return $plasmidRepository->createQueryBuilder('plasmid');
                }
            ])
            ->add('oligos', EntityType::class, [
                'class' => Oligo::class,
                'multiple' => true,
                'query_builder' => function (OligoRepository $oligoRepository) {
                    return $oligoRepository->createQueryBuilder('oligo');;
                }
            ])
            ->add('numberOfClones', IntegerType::class, [
                'mapped' => false,
            ]);

        // The form will accept data, but if one of the collection fields is left empty,
        // it will create an empty field inside the collection.
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $strains_in = $form->getData()->getStrainsIn();
                if (count($strains_in) == 0) {
                    $form->add('strainsIn', CollectionType::class, [
                        'entry_type' => StrainPickerType::class,
                        'allow_add' => false,
                        'required' => true,
                    ]);
                    $form->get('strainsIn')->setData([null]);
                } elseif (count($strains_in) == 1) {
                    $form->add('strainsIn', CollectionType::class, [
                        'entry_type' => EntityType::class,
                        'allow_add' => false,
                        'required' => true,
                        'attr' => ['readonly' => true],
                        'entry_options' => [
                            'class' => Strain::class,
                            'choices' => [$strains_in[0]]
                        ]
                    ]);
                }
            }

        );
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }
}
