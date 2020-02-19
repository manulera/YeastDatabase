<?php

namespace App\Controller;

use App\Entity\Promoter;
use App\Form\PromoterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/controller", name="controller.")
 */
class PromoterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('promoter/index.html.twig', [
            'controller_name' => 'PromoterController',
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {

        $promoter = new Promoter();
        $form = $this->createForm(PromoterType::class, $promoter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($promoter);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Promoter added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('promoter.index'));
        }

        // Return a view
        return $this->render('promoter/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a promoter entity.
     *
     * @Route("/{id}", name="show")
     */
    public function showAction(Promoter $promoter)
    {
        return $this->render('promoter/show.html.twig', [
            'controller_name' => 'PromoterController',
        ]);
    }
}
