<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;

class ESFallbackTestVehicles {


    public $username = TestShared::USERNAME_TESTER1;
    public $password = TestShared::PASSWORD;

    private $createVehicle;
    private $format;
    private $searchTerm;
    private $searchResult;
    private $searchType;
    private $vin = false;
    private $registration = false;

    private function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                (new UrlBuilder())
                    ->vehicleSearch()
                    ->queryParam(TestShared::SEARCH_PARAM, $this->searchTerm)
                    ->queryParam(TestShared::FORMAT_PARAM, $this->format)
                    ->queryParam(TestShared::TYPE_PARAM, $this->searchType)
            );

            $this->searchResult = $result['data'];
        }
    }

    public function success()
    {
        $this->searchResult = null;
        $this->fetchSearchResult();
        return $this->searchResult['resultCount'] === null ? 'false' : 'true';
    }

    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setCreateVehicle($createVehicle)
    {
        $this->createVehicle = $createVehicle;
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
} 