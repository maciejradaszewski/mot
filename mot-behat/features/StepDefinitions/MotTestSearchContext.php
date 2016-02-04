<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Response;
use DvsaCommon\Utility\DtoHydrator;
use PHPUnit_Framework_Assert as PHPUnit;

class MotTestSearchContext implements Context
{
    const SITE_NUMBER = 'V1234';

    /**
     * @var MotTest
     */
    private $motTest;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var MotTestContext
     */
    private $motTestContext;

    /**
     * @var Response
     */
    private $searchResponse;

    /**
     * @param MotTest $motTest
     */
    public function __construct(MotTest $motTest)
    {
        $this->motTest = $motTest;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->motTestContext = $scope->getEnvironment()->getContext(MotTestContext::class);
    }

    /**
     * @When I search for an MOT test
     */
    public function iSearchForAnMOTTest()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['siteNr' => self::SITE_NUMBER]
        );
    }

    /**
     * @When I search for an Invalid MOT test
     */
    public function iSearchForAnInvalidMOTTest()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['siteNr' => 'abcdefghijklmnopqrstuvwxyz']
        );
    }

    /**
     * @Then the MOT test data is returned
     */
    public function theMOTTestDataIsReturned()
    {
        $motTestNumber = $this->motTestContext->getMotTestNumber();
        $data = $this->searchResponse->getBody()['data']['data'];
        PHPUnit::assertArrayHasKey($motTestNumber, $data);
    }

    /**
     * @Then the MOT test is not found
     */
    public function theMOTTestIsNotFound()
    {
        $body = $this->searchResponse->getBody()->toArray();
        PHPUnit::assertEmpty($body['data']['data']);
    }

    /**
     * @When /^I search for an MOT tests history by (.*)$/
     */
    public function iSearchForAnMOTTestHistoryBy($type)
    {
        $params = [
            "dateFrom" => null,
            "dateTo" => null,
            "rowCount" => 1,
            "order" => "desc"
        ];

        switch($type) {
            case "site":
                $params["siteNumber"] = "V1234";
                break;
            case "vin":
                $params["vin"] = "VIN123456789";
                break;
            case "registration":
                $params["vrm"] = "ABCD123";
                break;
                break;
            case "testNumber":
                $params["testNumber"] = $this->motTestContext->getMotTestNumber();
                break;
        }

        $this->searchResponse = $this->motTest->searchMotTestHistory(
            $this->sessionContext->getCurrentAccessToken(),
            $params
        );

    }

    /**
     * @Then /^MOT test history for vehicle and type (.*) is returned$/
     *
     * @param $testType
     */
    public function motTestHistoryForVehicleIsReturned($testType)
    {
        PHPUnit::assertNotEmpty(
            $this->filterMotHistorySearchResponse($this->searchResponse, "ABCD123", $testType)
        );
    }

    /**
     * @Then /^MOT test history for vehicle and type (.*) is not returned$/
     *
     * @param $testType
     */
    public function motTestHistoryForVehicleIsNotReturned($testType)
    {
        PHPUnit::assertEmpty(
            $this->filterMotHistorySearchResponse($this->searchResponse, "ABCD123", $testType)
        );
    }

    protected function filterMotHistorySearchResponse($response, $registration, $testType)
    {
        $data = $response->getBody()["data"]["data"]->toArray();

        $result = array_filter($data, function ($value) use ($registration, $testType) {
            return $value["registration"] == $registration &&
            $value["testType"] == $testType;
        });

        return $result;
    }

    /**
     * @When /^I search for an MOT test with invalid Mot test number$/
     */
    public function iSearchForAnMOTTestWithInvalidMotTestNumber()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['testNumber' => '']
        );
    }

    /**
     * @When /^I search for an MOT test with missing VRM$/
     */
    public function iSearchForAnMOTTestWithMissingVRM()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['vehicleRegNr' => '']
        );
    }

    /**
     * @Then /^the search is failed with error "([^"]*)"$/
     */
    public function theSearchIsFailedWithError($expectedErrorMessage)
    {
        $errorArr = $this->searchResponse->getBody()['errors'];
        $foundError = false;
        for ($i = 0; $i < count($errorArr); $i++){
            if($errorArr[$i]['message'] == $expectedErrorMessage) {
                $foundError = true;
                break;
            }
        }
        PHPUnit::assertTrue($foundError, 'Error Message not found: ' . $expectedErrorMessage);
    }

    /**
     * @When /^I search for an MOT test with non\-existing VRM$/
     */
    public function iSearchForAnMOTTestWithNonExistingVRM()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['vehicleRegNr' => 'YYYYYY']
        );
    }

    /**
     * @Then /^the search will return no mot test$/
     */
    public function theSearchWillReturnNoMotTest()
    {
        $ResponseMessage = $this->searchResponse->getBody()['data']['resultCount'];
        PHPUnit::assertEquals($ResponseMessage, "0");
    }

    /**
     * @When /^I search for an MOT test with non\-existing mot test number$/
     */
    public function iSearchForAnMOTTestWithNonExistingMotTestNumber()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['testNumber' => '0000000000000']
        );
    }
}
