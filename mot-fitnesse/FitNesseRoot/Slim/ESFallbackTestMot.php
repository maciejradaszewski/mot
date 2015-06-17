<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class ESFallbackTestMot
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $usernameTester = TestShared::USERNAME_TESTER1;
    public $password = TestShared::PASSWORD;

    private $siteNumber;
    private $personId;
    private $vehicleId;
    private $vin;
    private $vrm;

    private $format;
    private $searchType;
    private $searchResult;

    private function setUrlBuilder()
    {
        $urlBuilder = (new UrlBuilder())->motTestsearch();
        $urlBuilder
            ->queryParam(TestShared::FORMAT_PARAM, TestShared::FORMAT_DATA_TABLES)
            ->queryParam(TestShared::SITE_NUMBER_PARAM, $this->siteNumber)
            ->queryParam(TestShared::TESTER_PARAM, $this->personId)
            ->queryParam(TestShared::VEHICLE_PARAM, $this->vehicleId)
            ->queryParam(TestShared::VIN_QUERY_PARAM, $this->vin)
            ->queryParam(TestShared::VRM_QUERY_PARAM, $this->vrm);

        return $urlBuilder;
    }

    private function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                $this->setUrlBuilder()
            );

            $this->searchResult = $result['data'];
        }

        return $this->searchResult;
    }

    public function success()
    {
        $this->searchResult = null;
        $this->fetchSearchResult();
        return $this->searchResult['resultCount'] === null ? 'false' : 'true';
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
    }

    public function resultCount()
    {
        return $this->searchResult['resultCount'];
    }

    public function totalResultCount()
    {
        return $this->searchResult['totalResultCount'];
    }

    public function actualDataCount()
    {
        return count((array)$this->searchResult['data']);
    }

    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
    }

    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    public function setVrm($vrm)
    {
        $this->vrm = $vrm;
    }
}
