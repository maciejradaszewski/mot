<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks api for list of test classes a particular site is authorised to test
 */
class Vm2545VehicleTestClasses
{
    private $username = TestShared::USERNAME_ENFORCEMENT;
    private $password = TestShared::PASSWORD;

    private $result;
    private $siteId;

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function success()
    {
        $curlHandle = $this->getCurlHandle();
        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);
        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    private function getCurlHandle()
    {
        return curl_init(
            (new UrlBuilder())
                ->vehicleTestingStation()
                ->routeParam('id', $this->siteId)
                ->toString()
        );
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function siteName()
    {
        if (isset($this->result['data'])) {
            /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
            $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($this->result['data']);
            return $dto->getName();
        }
    }

    public function vehicleTestClasses()
    {
        if (isset($this->result['data'])) {
            /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
            $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($this->result['data']);
            return $dto->getTestClasses();
        }
    }
}
