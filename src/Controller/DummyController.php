<?php

namespace App\Controller;

use App\Entity\Locus;
use App\Form\AlleleType;
use App\Repository\LocusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    public function __construct(LocusRepository $locusRepository)
    {
        $this->locusRepository = $locusRepository;
    }
    /**
     * @Route("/dummy", name="dummy")
     */
    public function index()
    {

        $form = $this->createForm(AlleleType::class);

        return $this->render('dummy/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/dummy_ajax", name="dummy_ajax")
     */
    public function ajaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(array('data_passed' => 'this is a json response'));
        }
        return new Response('This is not ajax!', 400);
    }

    public function createDummyForm(string $filter = '')
    {
        $builder = $this->createFormBuilder();
        if ($filter == '') {
            $builder->add(
                'Strain',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Locus::class,
                    'choices' => [],

                ]
            );
        } else {
            $builder->add(
                'Strain',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Locus::class,
                    'query_builder' => $this->locusRepository->getWithSearchQueryBuilder($filter)
                ]
            );
        }
        return $builder->getForm();
    }
}
