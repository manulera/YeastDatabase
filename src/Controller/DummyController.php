<?php

namespace App\Controller;

use App\DataClass\MarkerSwitchAlleleChunky;
use App\Entity\AlleleChunky;
use App\Entity\Locus;
use App\Entity\Marker;
use App\Form\MarkerSwitchAlleleChunkyType;
use App\Form\MolBiolType;
use App\Form\StrainPickerType;
use App\Form\StrainSourceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{

    /**
     * @Route("/dummy", name="dummy")
     */
    public function index(Request $request)
    {
        $allele_chunky_repository = $this->getDoctrine()->getRepository(AlleleChunky::class);
        $form = $this->createForm(MarkerSwitchAlleleChunkyType::class, [
            'originalAllele' => $allele_chunky_repository->find(2),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
        }
        return $this->render('dummy/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
