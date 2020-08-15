<?php

namespace App\Form;


use App\Entity\Allele;
use App\Entity\AlleleDeletion;
use App\Entity\AlleleChunky;
use App\Entity\Marker;
use App\Repository\AlleleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MarkerSwitchAlleleType extends AbstractType
{

    /**
     * @var AlleleRepository
     * An array of strings containing the controllers we will redirect to
     */
    private $alleleRepository;

    public function __construct(AlleleRepository $alleleRepository)
    {
        $this->alleleRepository = $alleleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'originalAllele',
            EntityType::class,
            [
                'class' => Allele::class,
                'choices' => []
            ]
        );
        foreach (['marker', 'nMarker', 'cMarker'] as $field) {
            $builder->add(
                $field,
                EntityType::class,
                [
                    'class' => Marker::class,
                    'required' => false
                ]
            );
        }

        $formModifier = function (FormEvent $event, $data) {

            if ($data === null) {
                return;
            }
            $form = $event->getForm();
            $allele = $data['originalAllele'];

            $form
                ->add(
                    'originalAllele',
                    EntityType::class,
                    [
                        'class' => Allele::class,
                        'choices' => [$allele]
                    ]
                );

            if (get_class($allele) != AlleleChunky::class) {
                $form->remove('cMarker');
                $form->remove('nMarker');
            }
            if (get_class($allele) != AlleleDeletion::class) {
                $form->remove('marker');
            }

            if (get_class($allele) == AlleleChunky::class) {
                if (!$allele->getCMarker()) {
                    $form->remove('cMarker');
                }
                if (!$allele->getNMarker()) {
                    $form->remove('nMarker');
                }
            } elseif (get_class($allele) == AlleleDeletion::class) {
                if (!$allele->getMarker()) {
                    $form->remove('marker');
                }
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
