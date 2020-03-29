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
    public function getGenotype($alleles, $plasmids = []): string
    {

        $allele_array = [];

        foreach ($alleles as $allele) {
            // Here some times you will pass the wt possibility which is null
            if ($allele) {
                $allele_array[] = $allele->getName();
            } else {
                $allele_array[] = "";
            }
        }

        // Some sorting function
        $allele_array = $this->sortAlleles($allele_array);

        $plasmid_array = [];


        $genotype_string = '';
        if (count($allele_array)) {
            $genotype_string .= implode(' ', $allele_array);
        }


        foreach ($plasmids as $plasmid) {
            $genotype_string .= ' + ' . $plasmid->getName();
        }

        return $genotype_string;
    }

    /**
     * Sorts the array following some logic
     *
     * @param array|string
     * @return array|string
     */
    public function sortAlleles($genotype_in)
    {
        return $genotype_in;
    }
}
