<?php

namespace UserAdmin\ViewModel\RecordDemoTestOutcome;

use DvsaClient\Entity\Person;

class DemoTestAssessment
{
    private $tester;
    private $vehicleClassGroup;
    private $searchQueryParams;

    public function __construct(Person $person, $vehicleClassGroup, array $searchQueryParams = [])
    {
        $this->tester = $person;
        $this->vehicleClassGroup = $vehicleClassGroup;
        $this->searchQueryParams = $searchQueryParams;
    }

    public function getTester()
    {
        return $this->tester;
    }

    public function getVehicleClassGroup()
    {
        return $this->vehicleClassGroup;
    }

    public function getSearchQueryParams()
    {
        return $this->searchQueryParams;
    }
}
