<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Organisation\OrganisationPositionDto;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;

/**
 * Class OrganisationPositionMapper
 *
 * @package DvsaClient\Mapper
 */
class OrganisationPositionMapper extends DtoMapper
{
    /**
     * @param $organisationId
     *
     * @return OrganisationPositionDto[]
     */
    public function fetchAllPositionsForOrganisation($organisationId)
    {
        $url = OrganisationUrlBuilder::position($organisationId);

        return $this->get($url);
    }

    public function createPosition($organisationId, $nomineeId, $roleId)
    {
        $apiUrl = OrganisationUrlBuilder::position($organisationId);
        $data = [
            'nomineeId' => $nomineeId,
            'roleId'    => $roleId
        ];

        return parent::post($apiUrl, $data);
    }

    /**
     * Removes position in organisation from a person
     *
     * @param $organisationId
     * @param $positionId
     */
    public function deletePosition($organisationId, $positionId)
    {
        $urlBuilder = OrganisationUrlBuilder::position($organisationId, $positionId);

        return parent::delete($urlBuilder->toString());
    }
}
