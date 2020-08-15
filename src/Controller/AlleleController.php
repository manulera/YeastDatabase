<?php

namespace App\Controller;

use App\Entity\Allele;
use App\Entity\AlleleChunky;
use App\Repository\AlleleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/allele", name="allele.")
 */
class AlleleController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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
        $serialized = $this->serializer->serialize($allele, 'json', ['groups' => ['allele']]);

        return $this->render(
            'allele/show.html.twig',
            [
                'allele' => $allele,
                'serialized_allele' => $serialized
            ]
        );
    }
}
