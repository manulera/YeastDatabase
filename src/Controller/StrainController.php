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

    private function getFilterFromUrl(Request $request): array
    {
        $filters = ['genotype', 'id', 'creator'];
        $out = [];
        foreach ($filters as $filter) {
            $out[$filter] = $request->query->get($filter, null, FILTER_SANITIZE_STRING);
        }
        return $out;
    }

    private function makeUrlFilter(array $filters): string
    {
        $out_url = $this->generateUrl('strain.index');
        foreach ($filters as $filter => $value) {
            if (strlen($value)) {
                $f = urlencode($filter);
                $out_url .= "?$f=" . urlencode($value);
            }
        }
        return $out_url;
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

        $qb = $strainRepository->findAllQueryBuilder($this->getFilterFromUrl($request));
        $strains = $qb->getQuery()->execute();

        // Create the filter form

        $formBuilder = $this->createFormBuilder()
            ->add('id', TextType::class, ['required' => false])
            ->add('genotype', TextType::class, ['required' => false])
            ->add('creator', TextType::class, ['required' => false])
            ->add('filter', SubmitType::class);

        $filterForm = $formBuilder->getForm();
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            //TODO this is probably not great
            $url = $this->makeUrlFilter($filterForm->getData());
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
        $delete_form = $this->createDeleteForm($strain);
        $children = [];
        $sources = $strain->getStrainSourcesIn();
        foreach ($sources as $source) {
            $children = array_merge($children, $source->getStrainsOut()->ToArray());
        }
        return $this->render('strain/show.html.twig', array(
            'strain' => $strain,
            'delete_form' => $delete_form->createView(),
            'children' => $children
        ));
    }

    /**
     * Deletes a strain entity.
     *
     * @Route("/delete/{id}", name="delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Strain $strain)
    {
        $form = $this->createDeleteForm($strain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // The only instance where you should not be allowed to delete the entry is
            // if it has children. Otherwise it should give a warning if you will lose alleles,
            // plasmids,

            if (count($strain->getStrainSourcesIn())) {
                $this->addFlash('error', 'You cannot delete this strain because it has children!');
                return $this->redirectToRoute('strain.show', ['id' => $strain->getId()]);
            }

            $em = $this->getDoctrine()->getManager();


            $source = $strain->getSource();

            // If you orphan an allele, it should be deleted (you are sure the allele only 
            // exist in this strain because you already checked this strain has no children)
            $alleles = $source->getAlleles();
            foreach ($alleles as $allele) {
                $em->remove($allele);
            }
            // If you orphan the StrainSource, it should be deleted
            if (count($source->getStrainsOut()) == 1) {
                $em->remove($source);
            }
            // Finally remove the strain
            $em->remove($strain);
            $em->flush();
        }

        return $this->redirectToRoute('strain.index');
    }

    private function createDeleteForm(Strain $strain)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('strain.delete', array('id' => $strain->getId())))
            ->setMethod('DELETE')
            ->add('Delete', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger',
                ],
            ])
            ->getForm();
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
                'rest' => strval($source),
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
