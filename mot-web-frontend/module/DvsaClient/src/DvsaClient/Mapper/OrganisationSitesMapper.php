<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;

/**
 * Class OrganisationSitesMapper
 *
 * @package DvsaClient\Mapper
 */
class OrganisationSitesMapper extends DtoMapper
{
    /**
     * @param $organisationId
     *
     * @return VehicleTestingStationDto
     */
    public function fetchAllForOrganisation($organisationId)
    {
        $apiUrl = OrganisationUrlBuilder::sites($organisationId);

        return $this->get($apiUrl);
    }
}
