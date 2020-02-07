<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/dummy", name="dummy")
     */
    public function index()
    {
        $data_passed = "No Ajax request";

        return $this->render('dummy/index.html.twig', [
            'data_passed' => $data_passed,
        ]);
    }
    /**
     * @Route("/dummy_ajax", name="dummy_ajax")
     */
    public function ajaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(array('data_passed' => 'this is a json response'));
        }
        return new Response('This is not ajax!', 400);
    }
}
