<?php

namespace App\Form;

use App\Entity\Allele;
use App\Entity\Locus;
use App\Repository\LocusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('locus', EntityType::class, [
                'required' => true,
                'class' => Locus::class,
                'query_builder' => function () {
                    return $this->locusRepository->getWithSearchQueryBuilder()->setMaxResults(10);
                },
                'row_attr' => ['style' => 'display:inline-block;']

            ])
            ->add('filterLoci', TextType::class, ['mapped' => false, 'row_attr' => ['style' => 'display:inline-block;']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Allele::class,
        ]);
    }
}
