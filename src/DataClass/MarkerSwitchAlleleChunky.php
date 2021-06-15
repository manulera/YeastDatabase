<?php

namespace App\DataClass;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Marker;
use App\Entity\AlleleChunky;

class MarkerSwitchAlleleChunky
{
    public function __construct(array $in_array  = [])
    {
        foreach ($in_array as $key => $value) {
            $this->$key = $value;
        }
        // dump($this, $in_array);
    }

    /**
     * @var Marker
     */
    public $nMarker;

    /**
     * @var Marker
     */
    public $cMarker;

    /**
     * @Assert\NotBlank()
     * @var AlleleChunky
     */
    public $alleleChunky;
}
