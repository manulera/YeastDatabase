<?php

namespace App\Controller;

use App\Entity\Strain;
use App\Entity\StrainSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\StrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * Lists all strain entities.
     *
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction(Request $request, StrainRepository $strainRepository)
    {
        // Filter the data
        $filter = $request->query->get('filter');
        $qb = $strainRepository->findAllQueryBuilder($filter);
        $strains = $qb->getQuery()->execute();

        // Create the filter form

        $formBuilder = $this->createFormBuilder()
            ->add(
                'filter',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'my-2 my-sm-0',
                        'placeholder' => 'Search...',
                    ]
                ]
            )
            ->add('Search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0 btn-sm',

                ],
            ]);

        $filterForm = $formBuilder->getForm();
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            //TODO this is probably not great
            $url = $this->generateUrl('strain.index') . '?filter=' . urlencode($filterForm->get('filter')->getData());
            return $this->redirect($url);
        } else {
            return $this->render('strain/index.html.twig', [
                'strains' => $strains,
                'form' => $filterForm->createView()
            ]);
        }
    }

    /**
     * Finds and displays a strain entity.
     *
     * @Route("/{id}", name="show", requirements={"id":"\d+"})
     * @Method("GET")
     */
    public function showAction(Strain $strain)
    {
        return $this->render('strain/show.html.twig', array(
            'strain' => $strain
        ));
    }


    /**
     * Shows the database network
     *
     * @Route("/network", name="network")
     * @Method("GET")
     */
    public function networkAction()
    {
        $json = $this->getNetworkJson();

        return $this->render(
            'strain/network.html.twig',
            ['network_json' => $json]
        );
    }

    public function getNetworkJson()
    {
        $strains = $this->getDoctrine()->getRepository(Strain::class)->findAll();
        $sources = $this->getDoctrine()->getRepository(StrainSource::class)->findAll();
        $nodes = [];
        $links = [];
        $info = [];
        $id_count = 1;
        $strain_ids = [];

        foreach ($strains as $strain) {
            $strain_ids[$strain->getId()] = $id_count;
            $nodes[] = ['id' => $id_count, 'name' => 'strain_' . strval($id_count), 'class' => 'strain'];
            $info[] = [
                'main' => 'Strain ' . strval($strain->getId()),
                'rest' => $strain->getGenotype(),
            ];
            $id_count++;
        }

        foreach ($sources as $source) {
            $source_id = $id_count;
            $tags = $source->getStrainSourceTags()->toArray();
            $tags = array_map('strval', $tags);
            $source_name = 'source_' . implode('_', $tags) . '_' . strval($source->getId());
            $nodes[] = ['id' => $source_id, 'name' => $source_name];
            $info[] = [
                'main' => 'Source ' . strval($source->getId()),
                'rest' => 'The rest',
            ];
            foreach ($source->getStrainsIn() as $strain_in) {
                $strain_in_id = $strain_ids[$strain_in->getId()];
                $links[] = ['source' => $strain_in_id, 'target' => $source_id];
            }

            foreach ($source->getStrainsOut() as $strain_out) {
                $strain_out_id = $strain_ids[$strain_out->getId()];
                $links[] = ['source' => $source_id, 'target' => $strain_out_id];
            }
            $id_count++;
        }

        return json_encode(['nodes' => $nodes, "links" => $links, 'info' => $info]);
    }
}
