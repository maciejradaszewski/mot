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
        return $this->getFieldFromObject('name');
    }

    public function vehicleTestClasses()
    {
        return $this->getFieldFromObject('roles');
    }

    private function getFieldFromObject($field)
    {
        return isset($this->result['data']['vehicleTestingStation'])
            ? $this->result['data']['vehicleTestingStation'][$field] : '';
    }
}
