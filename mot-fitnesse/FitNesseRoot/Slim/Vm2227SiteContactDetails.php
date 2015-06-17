<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks api for contact details of a particular site
 */
class Vm2227SiteContactDetails
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

    public function addressLine1()
    {
        return $this->getContactFieldFromObject('addressLine1');
    }

    public function addressLine2()
    {
        return $this->getContactFieldFromObject('addressLine2');
    }

    public function addressLine3()
    {
        return $this->getContactFieldFromObject('addressLine3');
    }

    public function town()
    {
        return $this->getContactFieldFromObject('town');
    }

    public function postcode()
    {
        return $this->getContactFieldFromObject('postcode');
    }

    public function phoneNumber()
    {
        return isset($this->result['data']['vehicleTestingStation']['contacts'][0]['phones'][0]['number'])
            ? $this->result['data']['vehicleTestingStation']['contacts'][0]['phones'][0]['number'] : '';
    }

    private function getContactFieldFromObject($field)
    {
        return isset($this->result['data']['vehicleTestingStation']['contacts'][0]['address'])
            ? $this->result['data']['vehicleTestingStation']['contacts'][0]['address'][$field] : '';
    }
}
