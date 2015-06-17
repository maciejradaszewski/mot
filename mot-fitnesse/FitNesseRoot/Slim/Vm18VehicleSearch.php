<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm18VehicleSearch
{
    const STRING_VIN_TYPE_FULL_VIN = 'Full Vin';
    const STRING_VIN_TYPE_PARTIAL_VIN = 'Partial Vin';
    const STRING_VIN_TYPE_NO_VIN = 'No Vin';

    const VIN_TYPE_FULL_VIN = 'fullVin';
    const VIN_TYPE_PARTIAL_VIN = 'partialVin';
    const VIN_TYPE_NO_VIN = 'noVin';

    const EXACT_MATCH = 'EXACT-MATCH';
    const MULTIPLE_MATCHES = 'MULTIPLE-MATCHES';
    const NO_MATCH = 'NO-MATCH';
    const TOO_MANY_MATCHES = 'TOO-MANY-MATCHES';
    const RETEST = 'Retest';

    private $username = 'tester1';
    private $password = TestShared::PASSWORD;
    private $registration;
    private $vin;
    private $searchDvla;
    private $vehicle;
    private $vehicles;
    private $resultType;
    private $vinType = 0;
    private $error = null;
    private $isRetest = false;

    public function setRegistration($value)
    {
        $this->registration = $value;
    }

    public function setTestType($value)
    {
        if ($value === self::RETEST) {
            $this->searchDvla = 'true';
            $this->isRetest = true;
        } else {
            $this->searchDvla = 'false';
            $this->isRetest = false;
        }
    }

    public function setVinType($value)
    {
        switch ($value) {
            case self::STRING_VIN_TYPE_FULL_VIN:
                $vinType = self::VIN_TYPE_FULL_VIN;
                break;
            case self::STRING_VIN_TYPE_PARTIAL_VIN:
                $vinType = self::VIN_TYPE_PARTIAL_VIN;
                break;
            default:
                $vinType = self::VIN_TYPE_NO_VIN;
                break;
        }
        $this->vinType = $vinType;
    }

    public function setVin($value)
    {
        $this->vin = $value;
    }

    public function resultType()
    {
        if ($this->isRetest) {
           return $this->retrieveVehiclesForRetest();
        }

        return $this->retrieveVehicles();
    }

    private function retrieveVehicles()
    {
        $curlHandle = curl_init(
            (new UrlBuilder())->vehicleList()->queryParams(
                array(
                    TestShared::VIN_QUERY_PARAM => $this->vin,
                    TestShared::REG_QUERY_PARAM => $this->registration,
                    TestShared::EXCLUDE_DVLA_PARAM => $this->searchDvla,
                )
            )->toString()
        );
        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);

        $jsonResult = TestShared::execCurlForJson($curlHandle);

        if (array_key_exists('errors', $jsonResult)) {
            $this->error = $jsonResult['errors'][0];
            return '';
        } else {
            $this->error = null;
        }

        $this->vehicle = [];
        $vehicles = $jsonResult['data']['vehicles'];

        if ($vehicles) {
            $this->vehicle = current($vehicles);
        }

        return '';
    }

    private function retrieveVehiclesForRetest()
    {
        $curlHandle = curl_init(
            (new UrlBuilder())->vehicle()->queryParams(
                array(
                    TestShared::VIN_QUERY_PARAM => $this->vin,
                    TestShared::REG_QUERY_PARAM => $this->registration,
                    TestShared::VIN_TYPE_PARAM  => $this->vinType,
                    TestShared::EXCLUDE_DVLA_PARAM => $this->searchDvla,
                )
            )->toString()
        );
        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser($this->username, $this->password, $curlHandle);

        $jsonResult = TestShared::execCurlForJson($curlHandle);
        if (array_key_exists('errors', $jsonResult)) {
            $this->error = $jsonResult['errors'][0];
            return 'ERROR';
        } else {
            $this->error = null;
        }

        $this->resultType = $jsonResult['data']['resultType'];

        if ($this->resultType === self::EXACT_MATCH) {
            $this->vehicle = $jsonResult['data']['vehicle'];
        } elseif ($this->resultType === self::MULTIPLE_MATCHES) {
            $this->vehicles = $jsonResult['data']['vehicles'];
        } else {
            $this->vehicle = null;
            $this->vehicles = null;
        }

        return $this->resultType;
    }

    public function result()
    {
        if ($this->error) {
            if (!empty($this->error['displayMessage'])) {
                return $this->error['displayMessage'];
            } else {
                return $this->error['message'];
            }
        }

        if ($this->isRetest) {
            return $this->vehicleResultForRetest();
        }

        return $this->vehicleResult();
    }

    private function vehicleResult()
    {
        $vehicle = $this->vehicle;
        if ($vehicle) {
            return trim($vehicle['make']." ".$vehicle['model']." ".$vehicle['vin']." ".$vehicle['registration']);
        }

        return 'NOT FOUND';
    }

    private function vehicleResultForRetest()
    {
        switch($this->resultType) {
            case self::EXACT_MATCH:
                $vehicle = $this->vehicle;
                return $vehicle['make']." ".$vehicle['model']." ".$vehicle['vin']." ".$vehicle['registration'];
                break;
            case self::MULTIPLE_MATCHES:
                $numberOfResults = count($this->vehicles);
                return "$numberOfResults results found";
                break;
            case self::TOO_MANY_MATCHES:
                return 'Too many results returned';
                break;
            case self::NO_MATCH:
                return 'No results returned';
                break;
        }
    }

    public function setInfoAboutSearch()
    {
    }
}
