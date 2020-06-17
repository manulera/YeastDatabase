<?php

namespace App\Controller;

use App\Entity\Plasmid;
use App\Form\PlasmidType;
use App\Repository\PlasmidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    /**
     * Import a list of plasmids from a csv file
     * @Route("/import", name="import")
     */
    public function importAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('File', FileType::class)
            ->add('Submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['File']->getData();

            $plasmids_array = $this->parseCSV($file);

            $em = $this->getDoctrine()->getManager();

            $plasmid_repository = $em->getRepository(Plasmid::class);

            $new_plasmids = 0;
            $existing_plasmids = 0;
            $changed_plasmids = 0;
            $total = 0;
            foreach ($plasmids_array as $plasmid_i) {
                $total++;
                $code = $plasmid_i['code'];
                $name = $plasmid_i['name'];

                $details = $plasmid_i['details'];
                $existing_plasmid = $plasmid_repository->findOneBy(['code' => $code]);

                if ($existing_plasmid) {
                    $existing_plasmids++;
                    // The plasmid has changed (TODO handle this properly)
                }
                // The plasmid is new
                else {
                    $plasmid = new Plasmid();
                    $plasmid->setName($name);
                    $plasmid->setCode($code);
                    $plasmid->setDetails($details);
                    $em->persist($plasmid);
                    $new_plasmids++;
                }
            }
            // Run the query
            $em->flush();
            $message = sprintf("From %u input plasmids, you added %u, modified %u, and %u were unchanged", $total, $new_plasmids, $changed_plasmids, $existing_plasmids);
            $this->addFlash('success', $message);
            return $this->redirect($this->generateUrl('plasmid.index'));
        }

        // Return a view
        return $this->render('plasmid/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function parseCsv(UploadedFile $file)
    {
        $input = fopen($file, 'r');
        $output = [];
        while (!feof($input)) {
            $line = fgets($input);
            $split = explode(",", $line);
            if (count($split) != 3) {
                continue;
            }
            $output[] = [
                'code' => trim($split[0]),
                'name' => trim($split[1]),
                'details' => trim($split[2]),
            ];
        }
        return $output;
    }
}
