<?php

namespace DvsaClient\Mapper;

use DvsaClient\Entity\Address;
use DvsaClient\Entity\Person;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaClient\Entity\SitePosition;
use DvsaClient\Entity\VehicleTestingStation;
use DvsaCommon\Date\Time;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class SiteMapper
 *
 * @package DvsaClient\Mapper
 */
class SiteMapper extends DtoMapper
{
    protected $entityClass = VehicleTestingStation::class;

    /**
     * @param int
     * @return VehicleTestingStationDto
     */
    public function getById($vtsId)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsById($vtsId);

        return DtoHydrator::jsonToDto($this->get($apiUrl));
    }

    /**
     * @param VehicleTestingStationDto $dto
     *
     * @return int the Site id
     */
    public function create(VehicleTestingStationDto $dto)
    {
        $url = VehicleTestingStationUrlBuilder::vtsById();

        return $this->post($url, DtoHydrator::dtoToJson($dto));
    }

    public function validate(VehicleTestingStationDto $dto)
    {
        $url = VehicleTestingStationUrlBuilder::vtsById();
        $dto->setIsNeedConfirmation(true);

        return $this->post($url, DtoHydrator::dtoToJson($dto));
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update($id, $data)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsById($id);

        $response = $this->put($apiUrl, $data);
        return $response['id'];
    }

    /**
     * Update Contact for specified site
     *
     * @param integer $siteId
     * @param SiteContactDto $contactDto
     * @return array
     */
    public function updateContactDetails($siteId, SiteContactDto $contactDto)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::contactUpdate($siteId, $contactDto->getId());

        return $this->client->put($apiUrl, DtoHydrator::dtoToJson($contactDto));
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function saveDefaultBrakeTests($id, $data)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::defaultBrakeTests($id);
        $this->put($apiUrl, $data);
    }

    /**
     * @param array $params
     * @return SiteListDto
     */
    public function search($params)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::search();
        return $this->post($apiUrl, $params);
    }
}
