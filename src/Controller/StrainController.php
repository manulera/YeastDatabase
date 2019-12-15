<?php

namespace App\Controller;

use App\Entity\CustomStrainSource;
use App\Entity\DeletionBahlerMethod;
use App\Entity\Strain;
use App\Entity\StrainSource;
use App\Form\CustomStrainSourceType;
use App\Form\DeletionBahlerMethodType;
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
    private $formTypes;

    public function __construct()
    {
        // We create a form with the types of forms one can have
        $this->formTypes = [
            'Custom' => CustomStrainSource::class,
            'Deletion Bahler method' => DeletionBahlerMethod::class
        ];
    }
    /**
     * Returns an array to pass as choice
     *
     * @return array
     */
    public function formTypesChoice()
    {
        $out = [];
        foreach (array_keys($this->formTypes) as $value) {
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
                'choices' => $this->formTypesChoice()
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
        $strain_source = new $this->formTypes[$method_name];
        $form = $this->createForm($strain_source->getFormClass(), $strain_source);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $strain_source->createStrains();
            dump($strain_source);
            // Include the value
            $em = $this->getDoctrine()->getManager();

            // Bidirectional relationships are not handled by Doctrine (it cannot persist
            // if the joincolumn is not null, because it will try to insert the strain into the
            // database, but needs the source, and viceversa)

            foreach ($strain_source->getStrainsOut() as $strain) {
                $strain->updateGenotype();
                $em->persist($strain);
            }
            $em->persist($strain_source);
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
}
