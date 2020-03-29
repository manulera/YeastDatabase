<?php
// A class to write genotypes
namespace App\Service;


class SequenceService
{

    // An array with the single letter codes of AAs
    private $aminoacids;

    // An array with the single letter codes of nucleotides
    private $nucleotides;

    public function __construct()
    {
        $this->aminoacids = str_split("ACDEFGHIKLMNPQRSTVWY");
        $this->nucleotides = str_split("ACGT");
    }

    public function getAminoAcids(): array
    {
        return $this->aminoacids;
    }

    public function getNucleotides(): array
    {
        return $this->nucleotides;
    }
}
