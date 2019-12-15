<?php

namespace App\Controller;

use App\Entity\Oligo;
use App\Form\OligoType;
use App\Repository\OligoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oligo", name="oligo.")
 */

class OligoController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(OligoRepository $oligoRepository)
    {
        $oligos = $oligoRepository->findAll();
        return $this->render('oligo/index.html.twig', [
            'oligos' => $oligos,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $oligo = new Oligo();
        $form = $this->createForm(OligoType::class, $oligo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($oligo);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Oligo added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('oligo.index'));
        }

        // Return a view
        return $this->render('oligo/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
