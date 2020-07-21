<?php

namespace App\Form;


use App\Entity\AlleleChunky;
use App\Entity\Marker;
use App\Repository\AlleleChunkyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

// I think this is the only solution. For now, I would stick to:
// you select the strain, then there are two fields, one for the AlleleChunky in the strain, and one for the AlleleDeletion, but
// they are rendered continously and then you can switch the C marker or whatever


class MarkerSwitchAlleleChunkyType extends AbstractType
{

    /**
     * @var AlleleChunkyRepository
     * An array of strings containing the controllers we will redirect to
     */
    private $alleleRepository;

    public function __construct(AlleleChunkyRepository $alleleRepository)
    {
        $this->alleleRepository = $alleleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'originalAllele',
            EntityType::class,
            [
                'class' => AlleleChunky::class,
                'choices' => []
            ]
        );
        $builder->add(
            'cMarker',
            EntityType::class,
            [
                'class' => Marker::class,
                'required' => false
            ]
        );
        $builder->add(
            'nMarker',
            EntityType::class,
            [
                'class' => Marker::class,
                'required' => false
            ]
        );

        $formModifier = function (FormEvent $event, $data) {

            if ($data === null) {
                return;
            }
            $form = $event->getForm();
            $form
                ->add(
                    'originalAllele',
                    EntityType::class,
                    [
                        'class' => AlleleChunky::class,
                        'choices' => [$data['originalAllele']]
                    ]
                );
            if ($data['originalAllele']->getNMarker()) {
                $form->add(
                    'nMarker',
                    EntityType::class,
                    [
                        'class' => Marker::class,
                        'required' => false
                    ]
                );
            } else {
                $form->remove('nMarker');
            }
            if ($data['originalAllele']->getCMarker()) {
                $form->add(
                    'cMarker',
                    EntityType::class,
                    [
                        'class' => Marker::class,
                        'required' => false
                    ]
                );
            } else {
                $form->remove('cMarker');
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $formModifier($event, $event->getData());
            }
        );
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $data['originalAllele'] = $this->alleleRepository->find($data['originalAllele']);
                $formModifier($event, $data);
            }
        );
    }
}
