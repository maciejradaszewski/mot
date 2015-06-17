<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class VTSDetailsBySite extends VTSDetails {

    private $siteNumber;

    public function __construct($siteNumber)
    {
        $this->siteNumber = $siteNumber;
    }

    protected function fetchSearchResult()
    {
        if ($this->searchResult == null) {

            $urlBuilder = new UrlBuilder();
            $urlBuilder->vehicleTestingStationBySiteNumber()
                ->routeParam('site', $this->siteNumber);
            $this->searchResult = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                $urlBuilder
            );
        }
        return $this->searchResult;
    }


} 