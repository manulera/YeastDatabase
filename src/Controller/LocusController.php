<?php

namespace App\Controller;

use App\Entity\Locus;
use App\Entity\LocusName;
use App\Entity\PombaseId;
use App\Form\LocusType;
use App\Repository\LocusRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/locus", name="locus.")
 */
class LocusController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request, LocusRepository $locusRepository, PaginatorInterface $paginator)
    {
        // TODO move this to some admin board
        $formBuilder = $this->createFormBuilder()
            ->add('UpdateLoci', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0 btn-sm',

                ],
            ]);
        $updateForm = $formBuilder->getForm();
        $updateForm->handleRequest($request);
        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            // We do nothing here
            // return $this->updateLoci();
        }
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
                    ],
                    'required' => false
                ]
            )
            ->add('Search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0 btn-sm',

                ],
            ]);
        $filterForm = $formBuilder->getForm();
        $filterForm->handleRequest($request);
        $filter = $request->query->get('filter', null, FILTER_SANITIZE_STRING);
        $queryBuilder = $locusRepository->getWithSearchQueryBuilder($filter);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );


        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $url = $this->generateUrl('locus.index') . '?filter=' . urlencode($filterForm->get('filter')->getData());
            return $this->redirect($url);
        }

        return $this->render('locus/index.html.twig', [
            'pagination' => $pagination,
            'updateForm' => $updateForm->createView(),
            'filterForm' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $locus = new Locus();
        $form = $this->createForm(LocusType::class, $locus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($locus);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Locus added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('locus.index'));
        }

        // Return a view
        return $this->render('locus/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a locus entity.
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     */
    public function showAction(Locus $locus)
    {
        return $this->render('locus/show.html.twig', array(
            'locus' => $locus
        ));
    }

    public function updateLoci()
    {
        $em = $this->getDoctrine()->getManager();

        $finder = new Finder();
        $finder->files()->in($this->getParameter('data_dir') . '/genes_json');
        foreach ($finder as $file) {

            $json = json_decode(file_get_contents($file->getPathname()), true);
            $locus = new Locus();

            // Not all loci have a name
            if ($json['name']) {
                $name = new LocusName();
                $name->setName($json['name']);
                $locus->setName($name);
            }

            $pombase_id = new PombaseId();
            $pombase_id->setPombaseId($json['id']);
            $locus->setPombaseId($pombase_id);

            $em->persist($locus);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('locus.index'));
    }
}
