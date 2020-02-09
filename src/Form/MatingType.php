<?php

namespace App\Form;

use App\Entity\Mating;
use App\Entity\Strain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MatingType extends AbstractType
{

    public function getGenotypeIndex(): array
    {
        $genotype_index = $this->session->get('genotype_index');
        if (null === $genotype_index) {
            return [];
        }
        return $genotype_index;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $genotype_index = $this->getGenotypeIndex();

        $builder
            ->add('strain1')
            ->add('strain2')
            ->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'none'])
            ->add('strain_choice', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => $genotype_index
            ]);
        $formModifier = function (Form $form, array $genotype_index) {
            $form->add('strain_choice', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => $genotype_index
                // 'choices' => [1, 2, 3]
                // 'data' => $a_key
                // 'expanded' => true
            ]);
        };

        // TODO this could be made an event listener PRE_SET_DATA I just dont
        // see it makes it clearer
        if (count($genotype_index)) {
            $builder
                ->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'dummy'])
                ->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary float-right',
                    ]
                ]);
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $formModifier($event->getForm(), $this->getGenotypeIndex());
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            // TODO: What is this syntax???
            function (FormEvent $event) use ($formModifier) {
                $formModifier($event->getForm(), $this->getGenotypeIndex());
            }
        );

        // $builder->get('strain_choice')->resetViewTransformers();
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     /** @var Mating|null $data */

        //     $formint = $event->getForm();
        //     $formint->op

        //     if (count($possible_strains)) {

        //         $formint->add('strain_choice', EntityType::class, [
        //             'class' => Strain::class,
        //             'choices' => $possible_strains,
        //             'mapped' => false,
        //             'by_reference' => true,

        //             // 'multiple' => true,
        //             // 'expanded' => true
        //         ]);
        //         $formint->add('dummy', TextareaType::class, ['mapped' => false, 'data' => 'dummy']);
        //         $formint->add('save', SubmitType::class, [
        //             'attr' => [
        //                 'class' => 'btn btn-primary float-right',
        //             ],
        //         ]);
        //     }
        // });


        // //TODO show options only if strains are set and different
        // /** @var Mating|null $data */
        // $data = $options['data'] ?? null;



        // $builder->get('strain1')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

        //     $form = $event->getForm();
        //     /** @var Mating|null $data */
        //     $data = $form->getData();
        //     $this->addCombinations($form->getParent(), $data->getStrain1(), $data->getStrain2());
        // });
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mating::class,
        ]);
    }

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
}
