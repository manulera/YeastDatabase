<?php

namespace App\Controller;

use App\Entity\CustomStrainSource;
use App\Entity\DeletionBahlerMethod;
use App\Entity\Locus;
use App\Entity\Mating;
use App\Entity\Strain;
use App\Entity\StrainSource;
use App\Form\CustomStrainSourceType;
use App\Form\DeletionBahlerMethodType;
use App\Form\MatingType;
use App\Repository\StrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * @Route("/strain", name="strain.")
 */

class StrainController extends AbstractController
{
    /**
     * @var array
     * An array of strings containing the types of the forms
     */
    private $strainSourceControllers;

    public function __construct()
    {
        // We create a form with the types of forms one can have
        $this->strainSourceControllers = [
            'Custom' => customStrainSource::class,
            'Deletion Bahler method' => DeletionBahlerMethod::class,
            'Mating' => Mating::class


        ];
    }
    /**
     * Returns an array to pass as choice
     *
     * @return array
     */
    public function strainSourceControllersChoice()
    {
        $out = [];
        foreach (array_keys($this->strainSourceControllers) as $value) {
            $out[$value] = $value;
        }
        return $out;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(StrainRepository $strainRepository)
    {
        $strains = $strainRepository->findAll();
        return $this->render('strain/index.html.twig', [
            'strains' => $strains,
        ]);
    }

    /**
     * @Route("/create", name="create.select_method")
     */
    public function create(Request $request)
    {

        $formBuilder = $this->createFormBuilder()
            ->add('Method', ChoiceType::class, [
                'choices' => $this->strainSourceControllersChoice()
            ])
            ->add('Choose', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $data = $form->getData();
            return $this->redirect($this->generateUrl(
                'strain.create.method',
                [
                    "method_name" => $data["Method"]
                ]
            ));
        }
        return $this->render('strain/create_select_method.html.twig', ['form' => $form->createView()]);
    }
    /**
     * Returns a view with the form of the specific strain type provided
     *
     * @Route("/create/{method_name}", name="create.method")
     * @param Request $request
     * @param string $method_name
     * @return Response
     */
    public function renderStrainForm(Request $request, $method_name)
    {
        /* @var $strain_source StrainSource */
        $strain_source = new $this->strainSourceControllers[$method_name];
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($strain_source->getFormClass(), $strain_source);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Create the strains
            $strain_source->createStrains($form);

            // One has to insert the strains instead of the strain source for the
            // cascading to work
            $em->persist($strain_source);

            foreach ($strain_source->getAlleles() as $allele) {
                $em->persist($allele);
            }
            foreach ($strain_source->getStrainsOut() as $strain) {
                $em->persist($strain);
                dump($strain->getSource());
            }


            // Run the query
            $em->flush();
            $this->addFlash('success', 'Strain created');
            return $this->redirect($this->generateUrl('strain.index'));
        }
        return $this->render(
            'strain/create_form.html.twig',
            [
                'form' => $form->createView(),
                'method_name' => $method_name,
            ]
        );
    }

    /**
     * @Route("/create/Mating/combinations", name="create.method.mating.get")
     */

    public function getStrainPair(Request $request)
    {
        $mating = new Mating();
        $repository = $this->getDoctrine()->getRepository(Strain::class);

        $strain1 = $repository->find($request->query->get('strain1'));
        $strain2 = $repository->find($request->query->get('strain2'));

        $mating->setStrain1($strain1);
        $mating->setStrain2($strain2);

        $form = $this->createForm(MatingType::class, $mating);


        return $this->render('strain/create_form_mating_possibilities.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
