<?php
// A class to write genotypes
namespace App\Service;


class SequenceService
{

    // An array with the single letter codes of AAs
    private $aminoacids;

    // An array with the single letter codes of nucleotides
    private $nucleotides;

    // An array with the genetic code
    private $codon2aa;

    // An array with the genetic code
    private $aa2codons;


    public function __construct()
    {
        $this->aminoacids = str_split("ACDEFGHIKLMNPQRSTVWY");
        $this->nucleotides = str_split("ACGT");

        $this->codon2aa = array(
            "TTT" => "F",
            "TTC" => "F",
            "TTA" => "L",
            "TTG" => "L",
            "CTT" => "L",
            "CTC" => "L",
            "CTA" => "L",
            "CTG" => "L",
            "ATT" => "I",
            "ATC" => "I",
            "ATA" => "I",
            "ATG" => "M",
            "GTT" => "V",
            "GTC" => "V",
            "GTA" => "V",
            "GTG" => "V",
            "TCT" => "S",
            "TCC" => "S",
            "TCA" => "S",
            "TCG" => "S",
            "CCT" => "P",
            "CCC" => "P",
            "CCA" => "P",
            "CCG" => "P",
            "ACT" => "T",
            "ACC" => "T",
            "ACA" => "T",
            "ACG" => "T",
            "GCT" => "A",
            "GCC" => "A",
            "GCA" => "A",
            "GCG" => "A",
            "TAT" => "Y",
            "TAC" => "Y",
            "TAA" => "Stop",
            "TAG" => "Stop",
            "CAT" => "H",
            "CAC" => "H",
            "CAA" => "Q",
            "CAG" => "Q",
            "AAT" => "N",
            "AAC" => "N",
            "AAA" => "K",
            "AAG" => "K",
            "GAT" => "D",
            "GAC" => "D",
            "GAA" => "E",
            "GAG" => "E",
            "TGT" => "C",
            "TGC" => "C",
            "TGA" => "Stop",
            "TGG" => "W",
            "CGT" => "R",
            "CGC" => "R",
            "CGA" => "R",
            "CGG" => "R",
            "AGT" => "S",
            "AGC" => "S",
            "AGA" => "R",
            "AGG" => "R",
            "GGT" => "G",
            "GGC" => "G",
            "GGA" => "G",
            "GGG" => "G"
        );
    }

    public function getAminoAcids(): array
    {
        return $this->aminoacids;
    }

    public function getNucleotides(): array
    {
        return $this->nucleotides;
    }

    public function updateAa2Codon()
    {
        $this->aa2codons = [];
        foreach ($this->codon2aa as $codon => $aa) {
            $this->aa2codons[$aa][] = $codon;
        }
    }
}
