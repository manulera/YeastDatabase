<?php

namespace App\Controller;

use App\Entity\CustomStrainSource;
use App\Entity\DeletionBahlerMethod;
use App\Entity\Mating;
use App\Entity\Strain;
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
        $session = $this->get('session');
        

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($strain_source->getFormClass(), $strain_source);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Create the strains
            
            $opt['possible_strains'] = $session->get('possible_strains');

            $strain_source->createStrains($form, $opt);

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

        if ($strain1 == $strain2) {
            return;
        }

        $mating->setStrain1($strain1);
        $mating->setStrain2($strain2);
        // Todo calculate the possibilities inside the mating class
        $possible_strains = $this->strainCombinations($strain1, $strain2);

        // $entityManager = $this->getDoctrine()->getManager();


        // TODO maybe a better way of passing the strains?
        // foreach ($possible_strains as $st) {
        //     $st = $entityManager->persist($st);
        // }
        // $entityManager->flush();

        // We construct an array in which the keys are the genotypes and
        // the values are the indexes of the strain in this list

        $genotype_index = [];

        for ($i = 0; $i < count($possible_strains); $i++) {
            $geno = $possible_strains[$i]->getGenotype();
            if ($geno) {
                $genotype_index[$possible_strains[$i]->getGenotype()] = $i;
            } else {
                $genotype_index["wt"] = $i;
            }
        }


        $form = $this->createForm(MatingType::class, $mating);

        //TODO implement serialize in the strain class?
        $session = $this->get("session");
        $session->set('possible_strains', $possible_strains);
        $session->set('genotype_index', $genotype_index);
        return $this->render('strain/create_form_mating_possibilities.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // TODO move this somewhere
    public function recursiveCombination($in_list, &$result_list, $depth, $current)
    {
        if ($depth == count($in_list)) {
            $result_list[] = $current;
            return;
        }
        for ($i = 0; $i < count($in_list[$depth]); $i++) {
            $temp = $current;
            $temp[] = $in_list[$depth][$i];
            $this->recursiveCombination($in_list, $result_list, $depth + 1, $temp);
        }
    }

    public function recursiveStrainCombination($in_list, &$result_list, $depth, $current)
    {
        if ($depth == count($in_list)) {
            $st = new Strain;
            foreach ($current as $alle) {
                if ($alle) {
                    $st->addAllele($alle);
                }
            }
            $result_list[] = $st;
            return;
        }
        for ($i = 0; $i < count($in_list[$depth]); $i++) {
            $temp = $current;
            $temp[] = $in_list[$depth][$i];
            $this->recursiveStrainCombination($in_list, $result_list, $depth + 1, $temp);
        }
    }


    public function strainCombinations(Strain $strain1, Strain $strain2)
    {
        // @var Collection|Allele[]
        $alleles1 = $strain1->getAllele();
        // @var Collection|Allele[]
        $alleles2 = $strain2->getAllele();

        $loci1 = [];
        $alleles = [];

        foreach ($alleles1 as $alle1) {
            /* @var $alle Allele */
            $loci1[$alle1->getLocus()->getName()] = $alle1;
        }

        foreach ($alleles2 as $alle2) {
            /* @var $alle Allele */
            $locus2_name = $alle2->getLocus()->getName();

            if (array_key_exists($locus2_name, $loci1)) {
                // We only include them if the alleles are different. In principle there would be no way to tell
                // the difference between the alleles!
                if ($loci1[$locus2_name] != $alle2) {
                    $alleles[] = [$loci1[$locus2_name], $alle2];
                } else {
                    $alleles[] = [$alle2];
                }
                unset($loci1[$locus2_name]);
            } else {
                $alleles[] = [null, $alle2];
            }
        }

        foreach ($loci1 as $locus => $alle1) {
            $alleles[] = [$alle1, null];
        }
        $out = array();
        $this->recursiveStrainCombination($alleles, $out, 0, array());
        $i = 1;
        foreach ($out as $st) {
            $st->updateGenotype();
            $i++;
        }
        return $out;
    }
}
