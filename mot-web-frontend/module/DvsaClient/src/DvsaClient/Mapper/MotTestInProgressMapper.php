<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\MotTesting\MotTestInProgressDto;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;

/**
 * Class MotTestInProgressMapper
 *
 * @package DvsaClient\Mapper
 */
class MotTestInProgressMapper extends DtoMapper
{

    /**
     * @param $vtsId
     *
     * @return MotTestInProgressDto[]
     */
    public function fetchAllForVts($vtsId)
    {
        $url = VehicleTestingStationUrlBuilder::testInProgress($vtsId)->toString();
        return $this->get($url);
    }
}
