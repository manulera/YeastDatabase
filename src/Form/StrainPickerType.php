<?php

namespace App\Form;

use App\Entity\Strain;
use App\Repository\StrainRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StrainPickerType extends AbstractType implements DataMapperInterface
{

    /** @var strainRepository */
    private $strainRepository;

    public function __construct(StrainRepository $strainRepository)
    {
        $this->strainRepository = $strainRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'filterById',
                TextType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'data' => $options['filterById']
                ]
            )
            ->add(
                'filterByGenotype',
                TextType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'data' => $options['filterByGenotype']
                ]
            )
            ->add('strain', EntityType::class, [
                'required' => true,
                'class' => Strain::class,
                'choices' => [],

            ])
            ->setDataMapper($this);

        $formModifier = function (Form $form, array $filter_array) {

            $id = $filter_array['filterById'];
            $genotype = $filter_array['filterByGenotype'];
            // We only filter in any of these criteria are met
            if (strlen($id) || strlen($genotype)) {
                $filterArray = ['genotype' => $genotype, 'id' => $id, 'creator' => null];
                $form->add('strain', EntityType::class, [
                    'required' => true,
                    'class' => Strain::class,
                    'query_builder' => $this->strainRepository->findAllQueryBuilder($filterArray)
                ]);
            }
        };
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $strain = $form->getData();
                if ($strain) {
                    $form->add('strain', EntityType::class, [
                        'required' => true,
                        'class' => Strain::class,
                        'choices' => [$strain],

                    ]);
                }
                $arr = [
                    'filterById' => $form->get('filterById')->getData(),
                    'filterByGenotype' => $form->get('filterByGenotype')->getData(),
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
            'filterById' => '',
            'filterByGenotype' => '',
            'data_class' => Strain::class,
        ]);
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $viewData = $forms['strain']->getData();
    }

    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }
        if (!$viewData instanceof Strain) {
            throw new UnexpectedTypeException($viewData, Strain::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        // initialize form field values    
        $forms['strain']->setData($viewData);
    }
}
