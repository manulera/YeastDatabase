<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Entity\Locus;
use App\Entity\Promoter;
use App\Entity\Tag;
use App\Form\MolBiolType;
use App\Service\Genotyper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/strain/new/molbiol", name="strain.source.molbiol.")
 */
class MolBiolController extends StrainSourceController
{
    public function __construct(Genotyper $genotyper)
    {
        parent::__construct($genotyper);
        $this->controllerPossibilities = [
            'Bahler Method: Deletion' => 'bahler.deletion',
            'Bahler Method: Promoter Change' => 'bahler.promoter',
            'Bahler Method: Promoter Change + N-term tag' => 'bahler.promoter_nTag',
            'Bahler Method: C-term tag' => 'bahler.cTag',
            'Custom' => 'custom',
        ];
    }

    protected function redirect2Child(string $choice)
    {
        $arr = explode(".", $choice);
        if (count($arr) == 2) {
            return $this->redirectToRoute('strain.source.molbiol.' . $arr[0] . '.new', ["option" => $arr[1]]);
        } else {
            return $this->redirectToRoute('strain.source.molbiol.' . $choice . '.new');
        }
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * @Route("/possibilities", name="possibilities")
     */
    public function getPossibilities(Request $request)
    {
        $choice = $request->query->get("choice");

        $form = $this->createForm(MolBiolType::class, null, ['choice' => $choice]);

        $allele = new Allele;
        $repository = $this->getDoctrine()->getRepository(Locus::class);
        $locus = $repository->find($request->query->get("locus"));
        $allele->setLocus($locus);

        if ($form->has('inputPromoter')) {
            $repository = $this->getDoctrine()->getRepository(Promoter::class);
            $promoter = $repository->find($form->get('inputPromoter')->getData());
            $allele->setPromoter($promoter);
        }
        if ($form->has('inputNtermTag')) {
            $repository = $this->getDoctrine()->getRepository(Tag::class);
            $tag = $form->get('inputNtermTag')->getData();
            //dd($tag);
            if ($tag) {
                $tag = $repository->find($tag);
                $allele->setTag($tag);
            }
        }
        if ($form->has('inputCtermTag')) {
            $repository = $this->getDoctrine()->getRepository(Tag::class);
            $tag = $repository->find($form->get('inputCtermTag')->getData());
            $allele->setTag($tag);
        }

        $allele->updateName();

        return $this->render(
            'strain/source/molbiol.html.twig',
            [
                'form' => $form->createView(),
                'allele' => $allele
            ]

        );
    }
}
