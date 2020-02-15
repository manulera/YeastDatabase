<?php
// A class to write genotypes
namespace App\Service;

use App\Entity\Allele;


class Genotyper
{
    /**
     * Returns a string with the genotype given a series of alleles
     *
     * @param array|Allele[] $alleles
     * @return string
     */
    public function getGenotype($alleles)
    {

        $genotype_array = [];

        foreach ($alleles as $allele) {
            // Here some times you will pass the wt possibility which is null
            if ($allele) {
                $genotype_array[] = $allele->getName();
            } else {
                $genotype_array[] = "";
            }
        }

        // Some sorting function
        $genotype_array = $this->sortGenotype($genotype_array);

        return implode(' ', $genotype_array);
    }

    /**
     * Sorts the array following some logic
     *
     * @param array|string
     * @return array|string
     */
    public function sortGenotype($genotype_in)
    {
        return $genotype_in;
    }
}
