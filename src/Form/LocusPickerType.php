<?php

namespace App\Form;

use App\Entity\Locus;
use App\Repository\LocusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocusPickerType extends AbstractType implements DataMapperInterface
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
                    'row_attr' => ['class' => 'filter-locus locus_name'],
                    'required' => false,
                    'mapped' => false,
                    'data' => $options['filterByLocusName']
                ]
            )
            ->add(
                'filterByPombaseId',
                TextType::class,
                [
                    'row_attr' => ['class' => 'filter-locus pombase_id'],
                    'required' => false,
                    'mapped' => false,
                    'data' => $options['filterByPombaseId']
                ]
            )
            ->add('locus', EntityType::class, [
                'required' => true,
                'class' => Locus::class,
                'choices' => [],

            ])
            ->setDataMapper($this);;

        $formModifier = function (Form $form, array $filter_array) {

            $filter_locus_name = $filter_array['filterByLocusName'];
            $filter_pombase_id = $filter_array['filterByPombaseId'];
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
                $arr = [
                    'filterByLocusName' => $form->get('filterByLocusName')->getData(),
                    'filterByPombaseId' => $form->get('filterByPombaseId')->getData(),
                ];
                $formModifier($form, $arr);
            }

        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $formModifier($form, $event->getData());
            }

        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'filterByLocusName' => '',
            'filterByPombaseId' => '',
            'data_class' => Locus::class,
        ]);
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $viewData = $forms['locus']->getData();
    }

    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }
        if (!$viewData instanceof Locus) {
            throw new UnexpectedTypeException($viewData, Locus::class);
        }
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['locus'] = $viewData;
        $forms['filterByPombaseId'] = $viewData->getPombaseId();
    }
}
