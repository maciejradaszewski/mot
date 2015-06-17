<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SearchMotTests
 */
class SearchMotTests
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $vin;
    private $vrm;
    private $siteNumber;
    private $tester;
    private $searchRecent;
    private $vehicleid;
    private $testid;

    private $row;

    private function fetchSearchResult()
    {
        return TestShared::execCurlForJsonFromUrlBuilder(
            $this,
            (new UrlBuilder())->motTestSearch()
                ->queryParam('vin', $this->vin)
                ->queryParam('vrm', $this->vrm)
                ->queryParam('siteNumber', $this->siteNumber)
                ->queryParam('tester', $this->tester)
                ->queryParam('searchRecent', $this->searchRecent)
                ->queryParam('vehicleId', $this->vehicleid)
                ->queryParam('sortDirection', 'DESC')
                ->queryParam('rowCount', 100)
                ->queryParam(TestShared::FORMAT_PARAM, TestShared::FORMAT_DATA_TABLES)
        );
    }

    public function success()
    {
        $this->row = null;
        $result = $this->fetchSearchResult();

        if ($result['data']['totalResultCount'] > 0) {
            $this->row = $result['data']['data'][$this->testid];
            return true;
        }
        return false;
    }

    public function status()
    {
        if ($this->row !== null) {
            return $this->row['status'];
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getVehicleid()
    {
        return $this->vehicleid;
    }

    /**
     * @param mixed $vehicleid
     */
    public function setVehicleid($vehicleid)
    {
        $this->vehicleid = $vehicleid;
    }

    public function number()
    {
        if ($this->row !== null) {
            return $this->row['motTestNumber'];
        }
        return false;
    }

    public function primaryColour()
    {
        if ($this->row !== null) {
            return $this->row['primaryColour'];
        }
        return false;
    }

    public function hasRegistration()
    {
        if ($this->row !== null) {
            return $this->row['hasRegistration'];
        }
        return false;
    }

    public function vin()
    {
        if ($this->row !== null) {
            return $this->row['vin'];
        }
        return false;
    }

    public function vrm()
    {
        if ($this->row !== null) {
            return $this->row['registration'];
        }
        return false;
    }

    public function make()
    {
        if ($this->row !== null) {
            return $this->row['make'];
        }
        return false;
    }

    public function model()
    {
        if ($this->row !== null) {
            return $this->row['model'];
        }
        return false;
    }

    public function testType()
    {
        if ($this->row !== null) {
            return $this->row['testType'];
        }
        return false;
    }

    public function site()
    {
        if ($this->row !== null) {
            return $this->row['siteNumber'];
        }
        return false;
    }

    public function username()
    {
        if ($this->row !== null) {
            return $this->row['testerUsername'];
        }
        return false;
    }

    /**
     * @param mixed $siteNumber
     */
    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
    }

    /**
     * @param mixed $tester
     */
    public function setTester($tester)
    {
        $this->tester = $tester;
    }

    /**
     * @param mixed $vin
     */
    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    /**
     * @param mixed $vrm
     */
    public function setVrm($vrm)
    {
        $this->vrm = $vrm;
    }

    /**
     * @param mixed $searchRecent
     */
    public function setSearchRecent($searchRecent)
    {
        $this->searchRecent = $searchRecent;
    }

    /**
     * @return mixed
     */
    public function getTestid()
    {
        return $this->testid;
    }

    /**
     * @param mixed $testid
     */
    public function setTestid($testid)
    {
        $this->testid = $testid;
    }





}

