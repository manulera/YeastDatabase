<?php
// A class to convert entities to json objects for displaying
namespace App\Service;

use App\Entity\AlleleChunky;


class EntityJsonGraphics
{
    /**
     * Returns a json object with the allele pieces
     *
     * @param AlleleChunky $allele
     * @param string $pos
     * @return string
     */

    public function allele2json($allele, $pos)
    {

        $out = [];
        // Add the basic name
        $out[] = ['name' => '', 'type' => 'main', 'y_position' => $pos];

        foreach ($allele->getTruncations() as $trunc) {
            $out[] = [
                'name' => strval($trunc),
                'type' => 'truncation',
                'start' => $trunc->getStart(),
                'finish' => $trunc->getFinish(),
                'y_position' => $pos
            ];
        }

        foreach ($allele->getPointMutations() as $pm) {
            $out[] = [
                'name' => strval($pm),
                'type' => 'pointMutation',
                'position' => $pm->getSequencePosition(),
                'y_position' => $pos
            ];
        }

        $n_term_index = -1;
        $n_tag = $allele->getNTag();
        $n_marker = $allele->getNMarker();
        $promoter = $allele->getPromoter();

        if ($n_tag) {
            $out[] = [
                'name' => $n_tag->getName(),
                'type' => 'tag',
                'ind' => $n_term_index,
                'y_position' => $pos
            ];
            $n_term_index -= 1;
        }

        if ($promoter) {
            $out[] = [
                'name' => $n_tag->getName(),
                'type' => 'tag',
                'ind' => $n_term_index,
                'y_position' => $pos
            ];
            $n_term_index -= 1;
        }
    }
}
