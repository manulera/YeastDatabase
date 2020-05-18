<?php

namespace App\Form;

use App\Entity\Allele;
use App\Repository\LocusRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlleleType extends AbstractType
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
            ->add('locus', LocusPickerType::class, [
                'required' => true,
                'mapped' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Allele::class,
        ]);
    }
}
