<?php
require_once 'configure_autoload.php';
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\TestBase;

class Vm2544VehicleTestingStationFacilityListing
{
    private $vehicleTestingStationId;

    private $expectedAtlCount;
    private $expectedOptlCount;
    private $expectedTptlCount;

    private $facilities;

    const AUTOMATED_TEST_LANE = 'ATL';
    const ONE_PERSON_TEST_LANE = 'OPTL';
    const TWO_PERSON_TEST_LANE = 'TPTL';

    public function setVehicleTestingStationId($vehicleTestingStationId)
    {
        $this->vehicleTestingStationId = $vehicleTestingStationId;
    }

    public function setExpectedAtlCount($value)
    {
        $this->expectedAtlCount = $value;
    }

    public function setExpectedOptlCount($value)
    {
        $this->expectedOptlCount = $value;
    }

    public function setExpectedTptlCount($value)
    {
        $this->expectedTptlCount = $value;
    }

    public function success()
    {
        $urlBuilder = (new \MotFitnesse\Util\UrlBuilder())
                        ->vehicleTestingStation()
                        ->routeParam('id', $this->vehicleTestingStationId);

        $curlHandle = TestShared::prepareCurlHandleToSendJsonWithCreds(
            $urlBuilder->toString(),
            TestShared::METHOD_GET,
            null,
            new \MotFitnesse\Util\FtEnfTesterCredentialsProvider()
        );

        $jsonResult = TestShared::executeAndReturnResponseAsArray($curlHandle);
        /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
        $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($jsonResult);

        $this->facilities = $dto->getFacilities();

        return $this->facilities == null ? false : true;
    }

    public function areAtlFacilitiesCorrect() {
        return $this->expectedAtlCount == $this->getCountOfFacilitiesPresentForType(self::AUTOMATED_TEST_LANE);
    }

    public function areOptlFacilitiesCorrect() {
        return $this->expectedOptlCount == $this->getCountOfFacilitiesPresentForType(self::ONE_PERSON_TEST_LANE);
    }

    public function areTptlFacilitiesCorrect() {
        return $this->expectedTptlCount == $this->getCountOfFacilitiesPresentForType(self::TWO_PERSON_TEST_LANE);

    }

    private function getCountOfFacilitiesPresentForType($facilityTypeCode) {
        if (isset($this->facilities[$facilityTypeCode])) {
            return count($this->facilities[$facilityTypeCode]);
        } else {
            return 0;
        }
    }
}
