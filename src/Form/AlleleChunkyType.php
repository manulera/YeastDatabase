<?php

namespace App\Form;

use App\Entity\AlleleChunky;
use App\Repository\LocusRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlleleChunkyType extends AlleleType
{
    public function __construct(LocusRepository $locusRepository)
    {
        parent::__construct($locusRepository);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options_parent = $options;
        unset($options_parent['fields2show']);
        parent::buildForm($builder, $options_parent);
        switch ($options['fields2show']) {
            case "promoter":
                $builder
                    ->add('nMarker')
                    ->add('promoter');
                break;
            case "promoter_nTag":
                $builder
                    ->add('nMarker')
                    ->add('promoter')
                    ->add('nTag');
                break;
            case "cTag":
                $builder
                    ->add('cTag')
                    ->add('cMarker');
                break;
            case "all":
                $builder
                    ->add('nMarker')
                    ->add('promoter')
                    ->add('nTag')
                    ->add('cTag')
                    ->add('cMarker');
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => AlleleChunky::class,
            'fields2show' => ''
        ]);
    }
}
