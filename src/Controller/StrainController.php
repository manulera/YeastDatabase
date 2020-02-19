<?php

namespace App\Controller;

use App\Entity\Strain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Repository\StrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function indexAction(StrainRepository $strainRepository)
    {
        $strains = $strainRepository->findAll();
        return $this->render('strain/index.html.twig', [
            'strains' => $strains,
        ]);
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
}
