<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SearchVehicles
 */
class SearchVehicles
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $rowCount = 10;
    private $format;
    private $searchTerm;
    private $searchResult;
    private $searchFilter;
    private $vin;
    private $registration;


    private function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                (new UrlBuilder())
                    ->vehicleSearch()
                    ->queryParam(TestShared::SEARCH_PARAM, $this->searchTerm)
                    ->queryParam(TestShared::ROW_COUNT_PARAM, $this->rowCount)
                    ->queryParam(TestShared::FORMAT_PARAM, $this->format)
                    ->queryParam(TestShared::TYPE_PARAM, $this->searchFilter)
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


    public function getSearchFilter()
    {
        return $this->searchFilter;
    }

    public function setSearchFilter($searchFilter)
    {
        $this->searchFilter = $searchFilter;
    }

    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getRegistration()
    {
        return $this->registration;
    }

    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    public function getVin()
    {
        return $this->vin;
    }

    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    public function resultCount()
    {
        return $this->searchResult['resultCount'];
    }

    public function searchFilter()
    {
        return $this->searchResult['searched']['searchType'];
    }

    public function totalResultCount()
    {
        return $this->searchResult['totalResultCount'];
    }

    public function actualDataCount()
    {
        return count((array)$this->searchResult['data']);
    }

    public function searchedFor()
    {
        return $this->searchResult['searched']['search'];
    }

    public function sortDirection()
    {
        return $this->searchResult['searched']['sortDirection'];
    }
    public function rowCount()
    {
        return $this->searchResult['searched']['rowCount'];
    }

    public function vin()
    {
        return $this->searchResult['searched']['vin'];
    }

    public function registration()
    {
        return $this->searchResult['searched']['registration'];
    }


}
