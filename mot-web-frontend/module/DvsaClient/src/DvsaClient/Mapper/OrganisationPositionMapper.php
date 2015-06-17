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
        $url = $this->createUrlBuilder($organisationId)->position()->toString();

        return $this->get($url);
    }

    public function post($organisationId, $nomineeId, $roleId)
    {
        $url = $this->createUrlBuilder($organisationId)->position()->toString();
        $data = ['nomineeId' => $nomineeId, 'roleId' => $roleId];
        $this->client->postJson($url, $data);
    }

    /**
     * Removes position in organisation from a person
     *
     * @param $organisationId
     * @param $positionId
     */
    public function delete($organisationId, $positionId)
    {
        $urlBuilder = (new OrganisationUrlBuilder())
            ->organisationById($organisationId)->position()->routeParam('positionId', $positionId);
        $this->client->delete($urlBuilder->toString());
    }

    /**
     * @param int $organisationId
     *
     * @return OrganisationUrlBuilder
     */
    private function createUrlBuilder($organisationId)
    {
        return OrganisationUrlBuilder::organisationById($organisationId);
    }
}
