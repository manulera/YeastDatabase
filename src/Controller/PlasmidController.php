<?php

namespace App\Controller;

use App\Entity\Plasmid;
use App\Form\PlasmidType;
use App\Repository\PlasmidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plasmid", name="plasmid.")
 */

class PlasmidController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PlasmidRepository $plasmidRepository)
    {
        $plasmids = $plasmidRepository->findAll();
        return $this->render('plasmid/index.html.twig', [
            'plasmids' => $plasmids,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $plasmid = new Plasmid();
        $form = $this->createForm(PlasmidType::class, $plasmid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($plasmid);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Plasmid added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('plasmid.index'));
        }

        // Return a view
        return $this->render('plasmid/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
