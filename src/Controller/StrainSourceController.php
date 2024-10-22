<?php

namespace App\Controller;

use App\Entity\StrainSource;
use App\Service\Genotyper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/strain/new", name="strain.source.")
 */
class StrainSourceController extends AbstractController
{
    /**
     * @var array
     * An array of strings containing the controllers we will redirect to
     */
    protected $controllerPossibilities;

    /**
     * @var Genotyper
     * An array of strings containing the controllers we will redirect to
     */
    protected $genotyper;

    public function __construct(Genotyper $genotyper)
    {
        // We create a form with the subroutes of the other controllers
        $this->controllerPossibilities = [
            'Import strain' => 'custom',
            'Transformation' => 'molbiol',
            'Mating' => 'mating',
            'Add plasmid' => 'plasmid'
        ];
        $this->genotyper = $genotyper;
    }

    protected function redirect2Child(string $choice)
    {
        return $this->redirectToRoute('strain.source.' . $choice . '.index');
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $formBuilder = $this->createFormBuilder()
            ->add('Method', ChoiceType::class, [
                'choices' => $this->controllerPossibilities
            ])
            ->add('Choose', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // TODO: Maybe there is a better way of doing this?
            return $this->redirect2Child($data["Method"]);
        }
        // dump($this->controllerPossibilities['Custom']);
        return $this->render('strain/source/index.html.twig', [
            'controller_name' => 'StrainSourceController',
            'form' => $form->createView()
        ]);
    }

    protected function persistStrainSource(StrainSource $strain_source)
    {

        $em = $this->getDoctrine()->getManager();
        // One has to insert the strains instead of the strain source for the
        // cascading to work

        $strain_source->setDate(new \DateTime(date('Y-m-d H:i:s')));
        $strain_source->setCreator($this->container->get('security.token_storage')->getToken()->getUser());
        $em->persist($strain_source);
        foreach ($strain_source->getAllelesOut() as $allele) {
            $em->persist($allele);
        }
        foreach ($strain_source->getStrainsOut() as $strain) {
            $em->persist($strain);
        }
        // Run the query
        $em->flush();
        $this->addFlash('success', 'Strain created');
        return $this->redirect($this->generateUrl('strain.index'));
    }
}
