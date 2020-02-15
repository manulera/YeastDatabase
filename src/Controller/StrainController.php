<?php

namespace App\Controller;

use App\Entity\Allele;
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
        $session->remove('genotype_index');
        if ($form->isSubmitted()) {
            // Create the strains

            $possible_arrays = $session->remove('possible_arrays');
            $chosen_arrays = $form->get("strain_choice")->getData();
            $alle_repo = $this->getDoctrine()->getRepository(Allele::class);
            foreach ($chosen_arrays as $i) {
                $strain = new Strain;
                $strain->addMating($strain_source);
                foreach ($possible_arrays[$i] as $allele_id) {
                    dump($allele_id);
                    $alle = $alle_repo->find($allele_id);
                    $strain->addAllele($alle);
                }
                $strain->updateGenotype($this->genotyper);
                $strain_source->addStrainsOut($strain);
            }


            $strain_source->createStrains($form);

            // One has to insert the strains instead of the strain source for the
            // cascading to work
            $em->persist($strain_source);

            // foreach ($strain_source->getAlleles() as $allele) {
            //     $em->persist($allele);
            // }
            foreach ($strain_source->getStrainsOut() as $strain) {
                $em->persist($strain);
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

    
}
