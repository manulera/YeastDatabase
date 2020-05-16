<?php

namespace App\Form;

use App\Entity\Locus;
use App\Repository\LocusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class dummyClass
{
}


class LocusPickerType extends AbstractType
{

    /** @var LocusRepository */
    private $locusRepository;

    public function __construct(LocusRepository $locusRepository)
    {
        $this->locusRepository = $locusRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'filterByLocusName',
                TextType::class,
                [
                    'data' => $options['filter_locus_name'],
                    'mapped' => false,
                    'row_attr' => ['class' => 'filter-locus locus_name']
                ]
            )
            ->add(
                'filterByPombaseId',
                TextType::class,
                [
                    'data' => $options['filter_pombase_id'],
                    'mapped' => false,
                    'row_attr' => ['class' => 'filter-locus pombase_id']
                ]
            )
            ->add('locus', EntityType::class, [
                'required' => true,
                'class' => Locus::class,
                'choices' => [],

            ]);

        $formModifier = function (Form $form) {

            $filter_locus_name = $form->get('filterByLocusName')->getData();
            $filter_pombase_id = $form->get('filterByPombaseId')->getData();
            // We only filter in any of these criteria are met
            if (strlen($filter_pombase_id) || strlen($filter_locus_name)) {
                $filter = "$filter_locus_name $filter_pombase_id";
                $form->add('locus', EntityType::class, [
                    'required' => true,
                    'class' => Locus::class,
                    'query_builder' => $this->locusRepository->getWithSearchQueryBuilder($filter),
                ]);
            }
        };
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $formModifier($form);
            }

        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $formModifier($form);
            }

        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'filter_locus_name' => '',
            'filter_pombase_id' => ''
        ]);
    }
}
