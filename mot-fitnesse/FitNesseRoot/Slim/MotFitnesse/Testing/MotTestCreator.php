<?php

namespace MotFitnesse\Testing;

use MotFitnesse\Util\UrlBuilder;

class MotTestCreator
{
    public function create(String $vrm)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->vehicle();
        //    'vehicleId=21&vehicleTestingStationId=1&primaryColour=Silver&hasRegistration=true'
    }
}
