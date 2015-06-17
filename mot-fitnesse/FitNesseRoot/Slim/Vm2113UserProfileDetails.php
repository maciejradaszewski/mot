<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class Vm2113UserProfileDetails
 */
class Vm2113UserProfileDetails
{
    private $userId;
    private $result;

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function title()
    {
        return $this->getFieldFromObject('title');
    }

    public function firstName()
    {
        return $this->getFieldFromObject('firstName');
    }

    public function middleName()
    {
        return $this->getFieldFromObject('middleName');
    }

    public function surname()
    {
        return $this->getFieldFromObject('surname');
    }

    public function dateOfBirth()
    {
        return $this->getFieldFromObject('dateOfBirth');
    }

    public function drivingLicenceNumber()
    {
        return $this->getFieldFromObject('drivingLicenceNumber');
    }

    public function drivingLicenceRegion()
    {
        return $this->getFieldFromObject('drivingLicenceRegion');
    }

    public function addressLine1()
    {
        return $this->getFieldFromObject('addressLine1');
    }

    public function addressLine2()
    {
        return $this->getFieldFromObject('addressLine2');
    }

    public function addressLine3()
    {
        return $this->getFieldFromObject('addressLine3');
    }

    public function town()
    {
        return $this->getFieldFromObject('town');
    }

    public function postcode()
    {
        return $this->getFieldFromObject('postcode');
    }

    public function emailAddress()
    {
        return $this->getFieldFromObject('email');
    }

    public function phoneNumber()
    {
        return $this->getFieldFromObject('phone');
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    private function prepareCurlHandle()
    {
        $url = (new UrlBuilder())->personalDetails()->routeParam('id', $this->userId)->toString();

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, 'tester1', TestShared::PASSWORD);
    }

    private function getFieldFromObject($field)
    {
        return isset($this->result['data']) ? $this->result['data'][$field] : '';
    }
}
