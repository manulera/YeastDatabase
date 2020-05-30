<?php

namespace App\Controller;

use App\Entity\Locus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SequenceJsonController extends AbstractController
{

    /**
     * @Route("/sequencejson", name="sequencejson")
     */
    public function getSequenceJson(Request $request)
    {
        $locus_id = $request->query->get('locus_id');
        $locusRepository = $this->getDoctrine()->getRepository(Locus::class);
        $pombase_id = $locusRepository->find($locus_id)->getPombaseId()->getPombaseId();
        // $pombase_id = $locusRepository->findOneBy(['name' => "ase1"])->getPombaseId()->getPombaseId();

        $finder = new Finder();
        $finder->files()->in($this->getParameter('data_dir') . "/genes_json")->name("$pombase_id.json");
        foreach ($finder as $file) {
            $json = json_decode(file_get_contents($file->getPathname()), true);
            return new JsonResponse($json);
        }
    }
}
