<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Repository\AlleleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/allele", name="allele.")
 */
class AlleleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(AlleleRepository $alleleRepository)
    {
        $alleles = $alleleRepository->findAll();
        return $this->render('allele/index.html.twig', [
            'controller_name' => 'AlleleController',
            'alleles' => $alleles
        ]);
    }

    /**
     * Finds and displays an allele entity.
     *
     * @Route("/{id}", name="show")
     * @Method("GET")
     */
    public function showAction(Allele $allele)
    {
        return $this->render('allele/show.html.twig', array(
            'allele' => $allele
        ));
    }
}
