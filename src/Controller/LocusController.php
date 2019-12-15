<?php

namespace App\Controller;

use App\Entity\Locus;
use App\Form\LocusType;
use App\Repository\LocusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/locus", name="locus.")
 */

class LocusController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(LocusRepository $locusRepository)
    {
        $loci = $locusRepository->findAll();
        return $this->render('locus/index.html.twig', [
            'loci' => $loci,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $locus = new Locus();
        $form = $this->createForm(LocusType::class, $locus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($locus);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Locus added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('locus.index'));
        }

        // Return a view
        return $this->render('locus/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
