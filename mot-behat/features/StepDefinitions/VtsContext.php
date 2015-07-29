<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Vts;
use PHPUnit_Framework_Assert as PHPUnit;
use DvsaCommon\Dto\Site\SiteListDto;
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

    private $siteManager1Data = null;
    private $siteManager2Data = null;

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
            $responseBody = $response->getBody();
            if (! is_object($responseBody)) {
                throw new Exception("createSite: responseBody is not an object: failed to create Vts");
            }
            $this->siteCreate = $responseBody->toArray()['data'];
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
        $this->iSearchForAnExistingVehicleTestingStationByParam(['siteName' => self::SITE_NAME]);
    }

    private function iSearchForAnExistingVehicleTestingStationByParam($params)
    {
        $params = array_merge(
            $params,
            [
                "pageNr" => 1,
                "rowsCount" => 10,
                "sortBy" => "site.name",
                "sortDirection" => "ASC",
                '_class' => '\\DvsaCommon\\Dto\\Search\\SiteSearchParamsDto',
            ]
        );
        $response = $this->vehicleTestingStation->searchVts($params, $this->sessionContext->getCurrentAccessToken());
        $this->resultContext = $response->getBody()->toArray()['data'];
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's number$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsNumber()
    {
        $this->iSearchForAnExistingVehicleTestingStationByParam(['siteNumber' => $this->siteCreate['siteNumber']]);
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's town$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsTown()
    {
        $this->iSearchForAnExistingVehicleTestingStationByParam(['siteTown' => self::SITE_TOWN]);
    }

    /**
     * @When /^I search for a existing Vehicle Testing Station by it's postcode$/
     */
    public function iSearchForAnExistingVehicleTestingStationByItsPostcode()
    {
        $this->iSearchForAnExistingVehicleTestingStationByParam(['sitePostcode' => self::SITE_POSTCODE]);
    }

    /**
     * @Then /^I should see the Vehicle Testing Station result$/
     */
    public function iShouldSeeTheVehicleTestingStationResult()
    {
        /** @var SiteListDto $result */
        $result = DtoHydrator::jsonToDto($this->resultContext);
        $site = $result->getData()[0];

        PHPUnit::assertInstanceOf(SiteListDto::class, $result);
        PHPUnit::assertEquals(self::SITE_NAME, $site['name']);
        PHPUnit::assertEquals(self::SITE_TOWN, $site['town']);
        PHPUnit::assertEquals(self::SITE_POSTCODE, $site['postcode']);
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
        PHPUnit::assertEquals(0, $result->getTotalResultCount());
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

    /**
     * @Given I attempt to assign the role of site manager to more than one user of a vehicle testing station
     */
    public function IAttemptToAssignTheRoleOfSiteManagerToMoreThanOneUserOfAVTS()
    {
        $testerService = $this->sessionContext->testSupportHelper->getTesterService();

        $this->siteManager1Data        = $testerService->create([
            'accountClaimRequired' => false,
            'siteIds'              => [1],
        ])->data;

        $this->siteManager2Data =  $testerService->create([
            'accountClaimRequired' => false,
            'siteIds'              => [1],
        ])->data;

        $params = [
            'nomineeId' => $this->siteManager1Data['personId'],
            'roleCode' => 'SITE-MANAGER'
        ];


        $result1 = $this->vehicleTestingStation->assignManager(
            $this->siteCreate['id'],
            $this->sessionContext->getCurrentAccessToken(),
            $params
        );

        $params['nomineeId'] =  $this->siteManager2Data['personId'];

        $result2 = $this->vehicleTestingStation->assignManager(
            $this->siteCreate['id'],
            $this->sessionContext->getCurrentAccessToken(),
            $params
        );

        PHPUnit::assertEquals(200, $result1->getStatusCode());
        PHPUnit::assertEquals(200, $result2->getStatusCode());

    }

    /**
     * @Then /^the roles should be assigned successfully$/
     */
    public function theRolesShouldBeAssignedSuccessfully()
    {
        $params = ['action' => "SITE-NOMINATION-ACCEPTED"];

        // login as user1
        $this->sessionContext->iMAuthenticatedWithMyUsernameAndPassword(
            $this->siteManager1Data['username'],
            $this->siteManager1Data['password']
        );

        // get first notification, the one we just added
        $user1NotificationId = $this->vehicleTestingStation->getSiteManagerNotification(
            $this->siteManager1Data['personId'],
            $this->sessionContext->getCurrentAccessToken()
        )->getBody()->toArray()['data'][0]['id'];

        $user1Response =
        $this->vehicleTestingStation->acceptSiteManagerNomination(
             $user1NotificationId,
             $this->sessionContext->getCurrentAccessToken(),
             $params
        );

        //login as user2
        $this->sessionContext->iMAuthenticatedWithMyUsernameAndPassword(
            $this->siteManager2Data['username'],
            $this->siteManager2Data['password']
        );

        // get first notification, the one we just added
        $user2NotificationId = $this->vehicleTestingStation->getSiteManagerNotification(
            $this->siteManager2Data['personId'],
            $this->sessionContext->getCurrentAccessToken()
        )->getBody()->toArray()['data'][0]['id'];

        $user2Response =
        $this->vehicleTestingStation->acceptSiteManagerNomination(
            $user2NotificationId,
            $this->sessionContext->getCurrentAccessToken(),
            $params
        );

        PHPUnit::assertEquals(200, $user1Response->getStatusCode());
        PHPUnit::assertEquals(200, $user2Response->getStatusCode());

    }
}
