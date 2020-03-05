<?php

namespace App\Form;

use App\Entity\Locus;
use App\Entity\Marker;
use App\Entity\MolBiol;
use App\Entity\Promoter;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MolBiolType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formModifier = function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->get('choice')->getData();
            if (!$data) {
                return;
            }
            foreach ($data as $keyword) {
                switch ($keyword) {
                    case "Promoter change":
                        $form->add('inputPromoter', EntityType::class, [
                            'class' => Promoter::class,
                            'mapped' => false
                        ]);
                        break;
                    case "N-terminal tagging":
                        $form->add('inputNtermTag', EntityType::class, [
                            'class' => Tag::class,
                            'mapped' => false
                        ]);
                        break;
                    case "C-terminal tagging":
                        $form->add('inputCtermTag', EntityType::class, [
                            'class' => Tag::class,
                            'mapped' => false
                        ]);
                        break;
                }
            }
            $form->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
        };

        $builder
            ->add('inputStrain')
            ->add('targetLocus', EntityType::class, [
                'class' => Locus::class,
                'mapped' => false
            ])
            ->add('choice', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Deletion',
                    'Promoter change',
                    'N-terminal tagging',
                    'C-terminal tagging',
                ],
                'choice_label' => function ($choice, $key, $value) {
                    return $choice;
                }

            ])
            ->add('marker', EntityType::class, [
                'class' => Marker::class,
                'mapped' => false
            ]);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {

                $form = $event->getForm();
                $options = $form->getConfig()->getOptions();
                // TODO maybe there is another way to trigger the post_set_data event
                // I just dont see how with unmapped fields
                $form->get('choice')->setData($options["choice"]);

                $formModifier($event);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MolBiol::class,
            'choice' => []
        ]);
    }
}
