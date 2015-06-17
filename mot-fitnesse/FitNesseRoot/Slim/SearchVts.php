<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SearchVts
 */
class SearchVts
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $rowCount = 10;
    private $format;
    private $searchTerm;
    private $searchResult;



    private function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $result = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                (new UrlBuilder())
                    ->vehicleTestingStationSearch()
                    ->queryParam(TestShared::SEARCH_PARAM, $this->searchTerm)
                    ->queryParam(TestShared::ROW_COUNT_PARAM, $this->rowCount)
                    ->queryParam(TestShared::FORMAT_PARAM, $this->format)
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

    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;
    }

    public function setFormat($format)
    {
        $this->format = $format;
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

    public function searchedFor()
    {
        return $this->searchResult['searched']['searchedFor'];
    }
    public function types()
    {
        return join(',', $this->searchResult['searched']['type']);
    }
    public function status()
    {
        return join(',', $this->searchResult['searched']['status']);
    }
    public function classes()
    {
        return join(',', $this->searchResult['searched']['classes']);
    }
    public function sortColumnId()
    {
        return $this->searchResult['searched']['sortColumnId'];
    }
    public function sortColumnName()
    {
        return $this->searchResult['searched']['sortColumnName'];
    }
    public function sortDirection()
    {
        return $this->searchResult['searched']['sortDirection'];
    }
    public function rowCount()
    {
        return $this->searchResult['searched']['rowCount'];
    }
    public function start()
    {
        return $this->searchResult['searched']['start'];
    }
}
