<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use Dvsa\Mot\Behat\Support\Api\Session;
use DvsaCommon\Model\VehicleTestingStation;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Api\Vts;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use Dvsa\Mot\Behat\Support\Response;

class VtsManagementContext implements Context
{
    /**
     * @var TestSupportHelper
     */
    protected $testSupportHelper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var SessionContext
     */
    protected $sessionContext;

    /**
     * @var Vts
     */
    protected $vehicleTestingStation;

    /**
     * @var array
     */
    protected $siteCreate;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $testingFacilitiesData = [
        'OPTL' => null,
        'TPTL' => null,
    ];

    /**
     * @var string
     */
    protected $siteStatus;

    /**
     * @var MotTestContext
     */
    protected $motTestContext;

    /**
     * @var VehicleContext
     */
    protected $vehicleContext;

    /**
     * @var VtsContext
     */
    protected $vtsContext;

    /**
     * @param TestSupportHelper $testSupportHelper
     * @param Session $session
     */
    public function __construct(TestSupportHelper $testSupportHelper, Session $session, Vts $vehicleTestingStation)
    {
        $this->testSupportHelper = $testSupportHelper;
        $this->vehicleTestingStation = $vehicleTestingStation;
        $this->session = $session;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(\SessionContext::class);
        $this->motTestContext = $scope->getEnvironment()->getContext(\MotTestContext::class);
        $this->vehicleContext = $scope->getEnvironment()->getContext(\VehicleContext::class);
        $this->vtsContext = $scope->getEnvironment()->getContext(\VtsContext::class);
    }

    /**
     * @Given a :name vehicle testing site exists
     */
    public function aVehicleTestingSiteExists($name)
    {
        $this->createSite($name);
    }

    /**
     * @When I configure its test lines to:
     */
    public function iConfigureItsTestLinesTo(TableNode $table)
    {
        $data = $table->getColumnsHash();
        $numOptl = (int) $data[0]['number of one person test lanes'];
        $numTptl = (int) $data[0]['number of two person test lanes'];

        $this->testingFacilitiesData['OPTL'] = $numOptl;
        $this->testingFacilitiesData['TPTL'] = $numTptl;

        $response = $this->vehicleTestingStation->updateTestingFacilities(
            $this->sessionContext->getCurrentAccessToken(),
            $this->siteCreate['id'],
            ['name' => $this->siteCreate['name']],
            $numOptl,
            $numTptl
        );

        $this->response = $response;
    }

    /**
     * @Then site details should be updated
     */
    public function siteDetailsShouldBeUpdated()
    {
        PHPUnit::assertEquals(200, $this->response->getStatusCode());
        $expectedBody = [
            'data' => [
                'success' => true,
            ],
        ];
        PHPUnit::assertSame($expectedBody, $this->response->getBody()->toArray());

        $facilities = $this->getVtsDetails()->getFacilities();

        PHPUnit::assertArrayHasKey('TPTL', $facilities);
        PHPUnit::assertArrayHasKey('OPTL', $facilities);
        PHPUnit::assertCount($this->testingFacilitiesData['TPTL'], $facilities['TPTL']);
        PHPUnit::assertCount($this->testingFacilitiesData['OPTL'], $facilities['OPTL']);
    }

    /**
     * @Then site testing classes shoud be removed
     */
    public function siteTestingClassesShoudBeRemoved()
    {
        PHPUnit::assertEquals(200, $this->response->getStatusCode());
        $expectedBody = [
            'data' => [
                'success' => true,
            ],
        ];
        PHPUnit::assertSame($expectedBody, $this->response->getBody()->toArray());
    }

    /**
     * @Then My changes should not be updated
     */
    public function myChangesShouldNotBeUpdated()
    {
        PHPUnit::assertEquals(400, $this->response->getStatusCode());
        $expectedBody = [
            'errors' => [
                [
                    "message" => "A number for either OPTL or TPTL must be selected",
                    "code" => 60,
                    "displayMessage" => "A number for either OPTL or TPTL must be selected",
                    "field" => "facilityOptl",
                ],
            ],
        ];
        PHPUnit::assertSame($expectedBody, $this->response->getBody()->toArray());

        $facilities = $this->getVtsDetails()->getFacilities();

        PHPUnit::assertArrayNotHasKey('TPTL', $facilities);
        PHPUnit::assertArrayHasKey('OPTL', $facilities);
        /*
         * 1 OPTL is added by default in Dvsa\Mot\Behat\Support\Api\Vts::generateSiteDto()
         * i.e. no changes have been made to the initial creation of a site
         */
        PHPUnit::assertCount(1, $facilities['OPTL']);
    }

    /**
     * @Then Site details should not be updated
     */
    public function siteDetailsShouldNotBeUpdated()
    {
        PHPUnit::assertEquals(403, $this->response->getStatusCode());
        $expectedErrors = [
            [
                'message' => 'Forbidden',
                'code' => 160,
            ],
        ];

        $responseArray = $this->response->getBody()->toArray();
        PHPUnit::assertArrayHasKey('errors', $responseArray);
        PHPUnit::assertSame($expectedErrors, $responseArray['errors']);
    }



    protected function createSite($name)
    {
        if (null === $this->siteCreate) {
            $ao = $this->testSupportHelper->getAreaOffice1Service()->create([]);
            $aoSession = $this->session->startSession($ao->data['username'], $ao->data['password']);

            $params = [
                'accessToken' => $aoSession->getAccessToken(),
                'name'        => $name,
                'town'        => 'test',
                'postcode'    => 'test',
            ];

            $response = $this->vehicleTestingStation->create($aoSession->getAccessToken(), $params);
            $responseBody = $response->getBody();

            if (!is_object($responseBody)) {
                throw new \Exception("createSite: responseBody is not an object: failed to create Vts");
            }

            $this->siteCreate = $responseBody->toArray()['data'];
            $this->siteCreate['name'] = $name;
        }
    }

    /**
     * @When I change the site status to Applied
     */
    public function iChangeTheSiteStatusToApplied()
    {
        $this->updateSiteDetails('AP');
    }

    /**
     * @When I change the site status to Lapsed
     */
    public function iChangeTheSiteStatusToLapsed()
    {
        $this->updateSiteDetails('LA');
    }

    /**
     * @When I change the site status to Approved
     */
    public function iChangeTheSiteStatusToApproved()
    {
        $this->updateSiteDetails('AV');
    }

    /**
     * @When I change the site status to Rejected
     */
    public function iChangeTheSiteStatusToRejected()
    {
        $this->updateSiteDetails('RJ');
    }

    /**
     * @When I change the site status to Retracted
     */
    public function iChangeTheSiteStatusToRetracted()
    {
        $this->updateSiteDetails('RE');
    }

    /**
     * @When I change the site status to Extinct
     */
    public function iChangeTheSiteStatusToExtinct()
    {
        $this->updateSiteDetails('EX');
    }

     /**
     * @When I remove all test classes
     */
    public function iRemoveAllTestClasses()
    {
        $this->response = $this->vehicleTestingStation->removeAllTestClasses(
            $this->sessionContext->getCurrentAccessToken(),
            $this->siteCreate['id']
        );
    }


    protected function updateSiteDetails($siteStatus)
    {
        $this->siteStatus = $siteStatus;
        $response = $this->vehicleTestingStation->updateSiteDetails(
            $this->sessionContext->getCurrentAccessToken(),
            $this->siteCreate['id'],
            [
                VehicleTestingStation::PATCH_PROPERTY_STATUS => $this->siteStatus,
                '_class' => VehicleTestingStationDto::class,
            ]
        );

        $this->response = $response;
    }

    /**
     * @Then my status should be updated
     */
    public function myStatusShouldBeUpdated()
    {
        PHPUnit::assertEquals(200, $this->response->getStatusCode());
        $expectedBody = [
            'data' => [
                'success' => true,
            ],
        ];
        PHPUnit::assertSame($expectedBody, $this->response->getBody()->toArray());

        $vtsDto = $this->getVtsDetails();

        PHPUnit::assertEquals($this->siteStatus, $vtsDto->getStatus());
    }


    /**
     * @return VehicleTestingStationDto
     */
    protected function getVtsDetails()
    {
        $response = $this->vehicleTestingStation->getVtsDetails(
            $this->siteCreate['id'],
            $this->sessionContext->getCurrentAccessToken()
        );

        /**
         * @var VehicleTestingStationDto $vtsDto
         */
         return \DvsaCommon\Utility\DtoHydrator::of()->doHydration($response->getBody()->toArray()['data']);
    }

    /**
     * @Given I log in as a tester assigned to newly created site with no test classes
     */
    public function iAmLoggedInAsATesterAssignedToSiteWithNoTestClasses()
    {
        $this->sessionContext->iAmLoggedInAsATesterAssignedToSites([$this->vtsContext->getSite()['id']]);
    }

    /**
     * @When /^I try to start MOT test$/
     */
    public function iTryToStartMOTTest()
    {
        $this->vehicleContext->createVehicle();
        $this->motTestContext->startMotTest(
            $this->sessionContext->getCurrentUserId(),
            $this->sessionContext->getCurrentAccessToken()
        );
    }

    /**
     * @Then /^I am not permitted to do this$/
     */
    public function iAmNotPermittedToDoThis()
    {
        $data = $this->motTestContext->getRawMotTestData()->getBody();
        PHPUnit::assertArrayHasKey('errors', $data);
    }
}