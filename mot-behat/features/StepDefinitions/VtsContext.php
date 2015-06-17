<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Vts;
use PHPUnit_Framework_Assert as PHPUnit;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\Dto\Site\SiteSearchDto;
use DvsaCommon\Utility\DtoHydrator;

class VtsContext implements Context
{
    const SITE_NAME = 'Behat Garage MOT';
    const SITE_TOWN = 'Toulouse';
    const SITE_POSTCODE = 'BS1 3LL';
    const SITE_TOWN_NO_GARAGE = 'Paris';

    /**
     * @var Vts
     */
    private $vehicleTestingStation;

    /** @var \SessionContext */
    private $sessionContext;
    private $siteCreate;
    private $resultContext;

    /**
     * @param Vts $vehicleTestingStation
     */
    public function __construct(Vts $vehicleTestingStation)
    {
        $this->vehicleTestingStation = $vehicleTestingStation;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(\SessionContext::class);
    }

    /**
     * @BeforeScenario @create-site
     */
    public function createSite()
    {
        if ($this->siteCreate === null) {
            $this->sessionContext->iAmLoggedInAsAnAreaOfficeUser();
            $params = [
                'accessToken' => $this->sessionContext->getCurrentAccessToken(),
                'name' => self::SITE_NAME,
                'town' => self::SITE_TOWN,
                'postcode' => self::SITE_POSTCODE,
            ];

            $response = $this->vehicleTestingStation->create($this->sessionContext->getCurrentAccessToken(), $params);
            $this->siteCreate = $response->getBody()->toArray()['data'];
            $this->sessionContext->testSupportHelper->getVtsService()->finishCreatingVtsWithHacking(
                $this->siteCreate['id'],
                [1, 2, 3, 4, 5, 7]
            );
        }
    }

    /**
     * @When /^I request information about a VTS$/
     */
    public function iRequestInformationAboutVts()
    {
        $this->resultContext = $this->vehicleTestingStation->getVtsDetails(
            $this->siteCreate['siteNumber'],
            $this->sessionContext->getCurrentAccessToken()
        );
    }

    /**
     * @Then /^the VTS details are returned$/
     */
    public function theVtsDetailsAreReturned()
    {
        PHPUnit::assertThat(
            $this->resultContext->getBody()['data']['vehicleTestingStation']['siteNumber'],
            PHPUnit::equalTo($this->siteCreate['siteNumber']), 'No VTS details returned for VTS Number'
        );
    }

    /**
     * @When /^I search for a VTS with "([^"]*)" "([^"]*)"$/
     * @param string $field
     * @param string $search
     */
    public function iSearchForVtsBySiteNumber($field, $search)
    {
        $params = [
            $field => $search,
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];

        $this->vehicleTestingStation->searchVts($params, $this->sessionContext->getCurrentAccessToken());
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's name$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsName()
    {
        $params = [
            'siteName' => self::SITE_NAME,
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];
        $this->iSearchForAnExistingVehicleTestingStationByParam($params);
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's number$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsNumber()
    {
        $params = [
            'siteNumber' => $this->siteCreate['siteNumber'],
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];
        $this->iSearchForAnExistingVehicleTestingStationByParam($params);
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's town$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsTown()
    {
        $params = [
            'siteTown' => self::SITE_TOWN,
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];
        $this->iSearchForAnExistingVehicleTestingStationByParam($params);
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's postcode$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsPostcode()
    {
        $params = [
            'sitePostcode' => self::SITE_POSTCODE,
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];
        $this->iSearchForAnExistingVehicleTestingStationByParam($params);
    }

    private function iSearchForAnExistingVehicleTestingStationByParam($params)
    {
        $response = $this->vehicleTestingStation->searchVts($params, $this->sessionContext->getCurrentAccessToken());
        $this->resultContext = $response->getBody()->toArray()['data'];
    }

    /**
     * @Then /^I should see the Vehicle Testing Station result$/
     */
    public function iShouldSeeTheVehicleTestingStationResult()
    {
        /** @var SiteListDto $result */
        $result = DtoHydrator::jsonToDto($this->resultContext);
        /** @var SiteSearchDto $site */
        $site = $result->getSites()[0];

        PHPUnit::assertInstanceOf(SiteListDto::class, $result);
        PHPUnit::assertInstanceOf(\DvsaCommon\Dto\Site\SiteSearchDto::class, $site);
        PHPUnit::assertEquals(self::SITE_NAME, $site->getSiteName());
        PHPUnit::assertEquals(self::SITE_TOWN, $site->getSiteTown());
        PHPUnit::assertEquals(self::SITE_POSTCODE, $site->getSitePostcode());
    }

    /**
     * @When /^I search for a Vehicle Testing Station only by a class$/
     */
    public function iSearchForVehicleTestingStationOnlyByClass()
    {
        $params = [
            'siteVehicleClass' => [1],
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];

        $response = $this->vehicleTestingStation->searchVts(
            $params,
            $this->sessionContext->getCurrentAccessToken()
        );
        PHPUnit::assertEquals(500, $response->getStatusCode());
        $this->resultContext = [
            '_class' => SiteListDto::class,
            'totalResult' => 0,
        ];
    }

    /**
     * @Then /^the search will return no results$/
     */
    public function theSearchWillReturnNoResult()
    {
        /** @var SiteListDto $result */
        $result = DtoHydrator::jsonToDto($this->resultContext);

        PHPUnit::assertInstanceOf(SiteListDto::class, $result);
        PHPUnit::assertEquals(0, $result->getTotalResult());
    }

    /**
     * @When /^I search for a town with no Vehicle Testing Station$/
     */
    public function iSearchForaTownWithNoVehicleTestingStation()
    {
        $params = [
            'siteTown' => self::SITE_TOWN_NO_GARAGE,
            '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto'
        ];

        $response = $this->vehicleTestingStation->searchVts(
            $params,
            $this->sessionContext->getCurrentAccessToken()
        );
        $this->resultContext = $response->getBody()->toArray()['data'];
    }
}
