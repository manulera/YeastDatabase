<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag", name="tag.")
 */

class TagController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();
        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($tag);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Tag added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('tag.index'));
        }

        // Return a view
        return $this->render('tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
