<?php

namespace App\Controller;

use App\Entity\Marker;
use App\Form\MarkerType;
use App\Repository\MarkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marker", name="marker.")
 */

class MarkerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(MarkerRepository $markerRepository)
    {
        $markers = $markerRepository->findAll();
        return $this->render('marker/index.html.twig', [
            'markers' => $markers,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $marker = new Marker();
        $form = $this->createForm(MarkerType::class, $marker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($marker);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Marker added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('marker.index'));
        }

        // Return a view
        return $this->render('marker/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
