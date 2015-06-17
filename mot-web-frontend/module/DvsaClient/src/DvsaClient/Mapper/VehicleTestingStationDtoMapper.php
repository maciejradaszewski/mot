<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

/**
 * Mapper to get VehicleTestingStationDto object from API
 *
 * @package DvsaClient\Mapper
 */
class VehicleTestingStationDtoMapper extends DtoMapper
{
    /**
     * @param integer $id
     *
     * @return \DvsaCommon\Dto\Site\VehicleTestingStationDto
     */
    public function getById($id)
    {
        $url = VehicleTestingStationUrlBuilder::vtsById($id)->queryParam('dto', true);
        return $this->get($url);
    }

    /**
     * @param string $siteNumber
     *
     * @return \DvsaCommon\Dto\Site\VehicleTestingStationDto
     */
    public function getBySiteNumber($siteNumber)
    {
        $url = VehicleTestingStationUrlBuilder::vtsBySiteNr($siteNumber)->queryParam('dto', true);
        return $this->get($url);
    }

    /**
     * Update Contact for specified site
     *
     * @param integer $siteId
     * @param SiteContactDto $contactDto
     */
    public function updateContactDetails($siteId, SiteContactDto $contactDto)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::contactUpdate($siteId, $contactDto->getId());

        $this->client->put($apiUrl, DtoHydrator::dtoToJson($contactDto));
    }
}
