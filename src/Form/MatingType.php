<?php

namespace App\Form;

use App\Entity\Mating;
use App\Entity\Strain;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('strain1')
            ->add('strain2')
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ])
            ->add('combinations', TextareaType::class, ['mapped' => false]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Mating|null $data */
            $data = $event->getData();
            $formint = $event->getForm();
            if ($data->getStrain1() && $data->getStrain2()) {
                $this->addCombinations($formint, $data->getStrain1(), $data->getStrain2());
            }
        });


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


    private function addCombinations(Form $form, Strain $strain1, Strain $strain2)
    {
        $form->remove('combinations');
        // if (null === $strain1 || null === $strain2) {

        //     return;
        // }

        $text_set = $strain1->__toString() . ' + ' .  $strain2->__toString();
        //strain1->__toString()
        $form->add('combinations', TextareaType::class, ['mapped' => false, 'data' => $text_set]);
    }
}
