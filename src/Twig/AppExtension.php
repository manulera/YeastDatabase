<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    // Quick test for referencing entities
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'refLocus',
                [$this, 'refLocus'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                ]
            )
        ];
    }

    public function refLocus(Environment $twig, $locus)
    {
        return $twig->render('foo.html.twig', ['locus' => $locus]);
    }
}
