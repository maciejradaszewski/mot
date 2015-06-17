<?php

namespace DvsaClient\Mapper;

use DvsaClient\Entity\Address;
use DvsaClient\Entity\Person;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaClient\Entity\SitePosition;
use DvsaClient\Entity\VehicleTestingStation;
use DvsaCommon\Date\Time;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

/**
 * Class VehicleTestingStationMapper
 *
 * @package DvsaClient\Mapper
 */
class VehicleTestingStationMapper extends Mapper
{
    protected $entityClass = VehicleTestingStation::class;

    /**
     * @param $organisationId
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetchAllForOrganisation($organisationId, $offset = 0, $limit = 15)
    {
        $apiUrl = OrganisationUrlBuilder::sites($organisationId)->toString() .
            $this->getPaginationUrlString($offset, $limit);

        $sites = $this->client->get($apiUrl);

        return $this->hydrateArrayOfEntities($sites['data']);
    }

    /**
     * @param int
     * @return array
     */
    public function getById($vtsId)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsById($vtsId);
        $result = $this->client->get($apiUrl);
        $vtsData = $result['data']['vehicleTestingStation'];

        $vtsData = $this->hydrateAddress($vtsData);
        $vtsData = $this->hydrateContacts($vtsData);
        $vtsData = $this->hydratePositions($vtsData);
        $vtsData = $this->hydrateSiteOpenHours($vtsData);

        return $vtsData;
    }

    /**
     * @param $siteNumber
     * @return mixed
     */
    public function getBySiteNumber($siteNumber)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsBySiteNr($siteNumber);
        $result = $this->client->get($apiUrl);
        $vtsData = $result['data']['vehicleTestingStation'];

        $vtsData = $this->hydrateAddress($vtsData);
        $vtsData = $this->hydrateContacts($vtsData);
        $vtsData = $this->hydratePositions($vtsData);
        $vtsData = $this->hydrateSiteOpenHours($vtsData);

        return $vtsData;
    }

    /**
     * @param $data array
     *
     * @return int the Site id
     */
    public function create($data)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsById();

        $response = $this->client->postJson($apiUrl, $data);
        return $response['data']['id'];
    }

    public function update($id, $data)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::vtsById($id);

        $response = $this->client->putJson($apiUrl, $data);
        return $response['data']['id'];
    }

    public function saveDefaultBrakeTests($id, $data)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::defaultBrakeTests($id);
        $this->client->putJson($apiUrl, $data);
    }

    /**
     * @param array                 $array
     * @param VehicleTestingStation $obj
     * @param array                 $params
     *
     * @return VehicleTestingStation
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function hydrateNestedEntities($array, $obj, $params)
    {
        $contactDetailsMapper = new ContactDetailMapper($this->client);
        $contactDetails = $contactDetailsMapper->hydrateArrayOfEntities($array['contacts']);
        $obj->setContactDetails($contactDetails);

        return $obj;
    }

    private function hydrateAddress($vtsData)
    {
        if (!empty($vtsData['address'])) {
            $address = new Address();
            $this->getHydrator()->hydrate($vtsData['address'], $address);
            $address->setTown($vtsData['address']['town']);
            $vtsData['address'] = $address;
        }
        return $vtsData;
    }

    private function hydrateSiteOpenHours($vtsData)
    {
        $weeklyOpeningHours = [];
        foreach ($vtsData['siteTestingDailySchedule'] as $dailyOpeningHoursData) {
            $dailyOpeningHours = new SiteDailyOpeningHours();
            $dailyOpeningHours->setWeekday($dailyOpeningHoursData['weekday']);

            $openTimeData = $dailyOpeningHoursData['openTime'];
            $openTime = $openTimeData ? Time::fromIso8601($openTimeData) : null;
            $dailyOpeningHours->setOpenTime($openTime);

            $closeTimeData = $dailyOpeningHoursData['closeTime'];
            $closeTime = $closeTimeData ? Time::fromIso8601($closeTimeData) : null;
            $dailyOpeningHours->setCloseTime($closeTime);

            $weeklyOpeningHours [] = $dailyOpeningHours;
        }
        $vtsData['siteOpeningHours'] = $weeklyOpeningHours;
        unset($vtsData['siteTestingDailySchedule']);
        return $vtsData;
    }

    private function hydrateContacts($vtsData)
    {
        $contactDetailsMapper = new ContactDetailMapper($this->client);
        $contactDetails = $contactDetailsMapper->hydrateArrayOfEntities($vtsData['contacts']);

        $vtsData['contacts'] = $contactDetails;

        return $vtsData;
    }

    private function hydratePositions($vtsData)
    {
        $positions = [];

        foreach ($vtsData['positions'] as $positionData) {
            $person = new Person();
            $this->getHydrator()->hydrate($positionData['person'], $person);

            $position = new SitePosition();
            $position->setRoleCode($positionData['role']);
            $position->setPerson($person);
            $position->setStatus($positionData['status']);
            $position->setActionedOn($positionData['actionedOn']);
            $position->setId($positionData['id']);

            $positions[] = $position;
        }

        $vtsData['positions'] = $positions;

        return $vtsData;
    }

    /**
     * @param array $params
     * @return SiteListDto
     */
    public function search($params)
    {
        $apiUrl = VehicleTestingStationUrlBuilder::search();
        $results = $this->client->post($apiUrl, $params);

        return (new DtoHydrator())->doHydration($results['data']);
    }
}
