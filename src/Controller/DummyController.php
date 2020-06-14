<?php

namespace App\Controller;

use App\Entity\Locus;
use App\Form\MolBiolAlleleChunkyType;
use App\Form\MolBiolType;
use App\Form\StrainPickerType;
use App\Form\StrainSourceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{

    /**
     * @Route("/dummy", name="dummy")
     */
    public function index(Request $request)
    {
        // $builder = $this->createFormBuilder();
        // $builder
        //     ->add('strain', StrainPickerType::class)
        //     ->add('submit', SubmitType::class, [
        //         'attr' => [
        //             'class' => 'btn btn-primary float-right',
        //         ]
        //     ]);
        // $form = $builder->getForm();
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     dump($form->getData());
        // }

        // return $this->render('dummy/index.html.twig', [
        //     'form' => $form->createView(),
        // ]);
        $form = $this->createForm(MolBiolAlleleChunkyType::class, null, ['allele_options' => ['fields2show' => 'all']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
        }
        return $this->render('dummy/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
