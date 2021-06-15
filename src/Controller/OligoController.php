<?php

namespace App\Controller;

use App\Entity\Oligo;
use App\Form\OligoType;
use App\Repository\OligoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/oligo", name="oligo.")
 */

class OligoController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction(OligoRepository $oligoRepository)
    {
        $oligos = $oligoRepository->findAll();
        return $this->render('oligo/index.html.twig', [
            'oligos' => $oligos,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $oligo = new Oligo();
        $form = $this->createForm(OligoType::class, $oligo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Include the value
            $em = $this->getDoctrine()->getManager();
            // Make the query
            $em->persist($oligo);
            // Run the query
            $em->flush();
            $this->addFlash('success', 'Oligo added!');
            // Redirect to the main view
            return $this->redirect($this->generateUrl('oligo.index'));
        }

        // Return a view
        return $this->render('oligo/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Import a list of oligos from a csv file
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

            $oligos_array = $this->parseCSV($file);

            $em = $this->getDoctrine()->getManager();

            $oligo_repository = $em->getRepository(Oligo::class);

            $new_oligos = 0;
            $existing_oligos = 0;
            $changed_oligos = 0;
            $total = 0;
            foreach ($oligos_array as $oligo_i) {
                $total++;
                $name = $oligo_i['name'];
                $sequence = $oligo_i['sequence'];
                $details = $oligo_i['details'];
                $existing_oligo = $oligo_repository->findOneBy(['name' => $name]);

                if ($existing_oligo) {
                    // The oligo existed as it is read
                    if ($sequence == $existing_oligo->getSequence()) {
                        $existing_oligos++;
                    }
                    // The oligo has changed (TODO handle this properly)
                    else {
                        $existing_oligo->setSequence($sequence);
                        $changed_oligos++;
                    }
                }
                // The oligo is new
                else {
                    $oligo = new Oligo();
                    $oligo->setName($name);
                    $oligo->setSequence($sequence);
                    $oligo->setDetails($details);
                    $em->persist($oligo);
                    $new_oligos++;
                }
            }
            // Run the query
            $em->flush();
            $message = sprintf("From %u input oligos, you added %u, modified %u, and %u were unchanged", $total, $new_oligos, $changed_oligos, $existing_oligos);
            $this->addFlash('success', $message);
            return $this->redirect($this->generateUrl('oligo.index'));
        }

        // Return a view
        return $this->render('oligo/import.html.twig', [
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
            // TODO: There is a trailing comma at the end of each line
            if (count($split) != 3) {
                continue;
            }
            $output[] = [
                'name' => trim($split[0]),
                'details' => trim($split[1]),
                'sequence' => strtoupper(str_replace(' ', '', trim($split[2])))
            ];
        }
        return $output;
    }
}
