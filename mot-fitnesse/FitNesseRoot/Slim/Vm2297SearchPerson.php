<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\PersonUrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks person returned by api
 */
class Vm2297SearchPerson
{
    private $result;
    private $personId;

    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    private function prepareCurlHandle()
    {
        $apiUrl = PersonUrlBuilder::byId($this->personId)->toString();

        return TestShared::prepareCurlHandleToSendJson(
            $apiUrl, TestShared::METHOD_GET, null, 'schememgt', TestShared::PASSWORD
        );
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function gender()
    {
        return $this->getFieldFromObject('gender');
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

    public function familyName()
    {
        return $this->getFieldFromObject('familyName');
    }

    public function dateOfBirth()
    {
        return $this->getFieldFromObject('dateOfBirth');
    }

    private function getFieldFromObject($field)
    {
        return isset($this->result['data']) ? $this->result['data'][$field] : '';
    }
}
