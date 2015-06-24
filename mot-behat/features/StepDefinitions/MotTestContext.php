<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
use Dvsa\Mot\Behat\Support\Api\ContingencyTest;
use Dvsa\Mot\Behat\Support\Api\DemoTest;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Dvsa\Mot\Behat\Support\Api\OdometerReading;
use Dvsa\Mot\Behat\Support\Api\ReasonForRejection;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class MotTestContext implements Context
{
    const SITE_NUMBER = 'V1234';

    private $brakeTestResultData;

    /**
     * @var Response
     */
    private $motTestData;

    /**
     * @var Response
     */
    private $motData;

    private $contingencyData;

    private $statusData;

    /**
     * @var string
     */
    private $inProgressMotTestNumber;

    /**
     * @var BrakeTestResult
     */
    private $brakeTestResult;

    /**
     * @var MotTest
     */
    private $motTest;

    /**
     * @var DemoTest
     */
    private $demoTest;

    /**
     * @var OdometerReading
     */
    private $odometerReading;

    /**
     * @var ReasonForRejection
     */
    private $reasonForRejection;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var VehicleContext
     */
    private $vehicleContext;

    /**
     * @var int
     */
    private $vehicleId;

    /**
     * @var ContingencyTestContext
     */
    private $contingencyTestContext;

    /**
     * @var ContingencyTest
     */
    private $contingencyTest;

    public function __construct(
        BrakeTestResult $brakeTestResult,
        MotTest $motTest,
        DemoTest $demoTest,
        ContingencyTest $contingencyTest,
        OdometerReading $odometerReading,
        ReasonForRejection $reasonForRejection,
        Session $session,
        TestSupportHelper $testSupportHelper
    ) {
        $this->brakeTestResult = $brakeTestResult;
        $this->motTest = $motTest;
        $this->demoTest = $demoTest;
        $this->contingencyTest = $contingencyTest;
        $this->odometerReading = $odometerReading;
        $this->reasonForRejection = $reasonForRejection;
        $this->session = $session;
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->vehicleContext = $scope->getEnvironment()->getContext(VehicleContext::class);
        $this->contingencyTestContext = $scope->getEnvironment()->getContext(ContingencyTestContext::class);
    }

    /**
     * @Then /^the MOT Test Status is "([^"]*)"$/
     * @Then /^the MOT Test Status should be "([^"]*)"$/
     *
     * @param $status
     */
    public function theMOTTestStatusIs($status)
    {
        $actualResponse = $this->statusData->getBody()['data']['status'];

        PHPUnit::assertEquals($status, $actualResponse, 'MOT Test Status is incorrect');
    }

    /**
     * @Given a logged in Tester, starts an MOT Test
     * @Given I start an MOT test as a Tester
     */
    public function iStartMotTestAsTester()
    {
        $this->sessionContext->iAmLoggedInAsATester();
        $this->startMotTest();
    }

    public function startMotTest()
    {
        $testClass = 4;
        $vehicleId = $this->vehicleContext->createVehicle(['testClass' => $testClass]);

        $this->motTestData = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $vehicleId,
            $testClass
        );
    }

    /**
     * @When I start a Demo MOT Test
     */
    public function iStartDemoTest()
    {
        $vehicleId = $this->vehicleContext->createVehicle();

        $this->motTestData = $this->demoTest->startMotTest(
            $this->sessionContext->getCurrentAccessToken(),
            $vehicleId
        );
    }

    /**
     * @Given I start a Demo MOT test as a Tester
     */
    public function iStartDemoMotTestAsTester()
    {
        $this->sessionContext->iAmLoggedInAsATester();
        $this->iStartDemoTest();
    }

    /**
     * @Given /^the Tester adds a Reason for Rejection$/
     */
    public function theTesterAddsAReasonForRejection()
    {
        $rfrId = $this->vehicleContext->getCurrentVehicleClass() < 3 ? 356 : 8455;
        $response = $this->reasonForRejection->addFailure($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber(), $rfrId);
        PHPUnit::assertSame(200, $response->getStatusCode());
    }

    /**
     * @When /^the Tester Fails the Mot Test$/
     */
    public function theTesterFailsTheMotTest()
    {
        $this->statusData = $this->motTest->failed(
            $this->sessionContext->getCurrentAccessToken(),
            $this->getMotTestNumber()
        );
    }

    /**
     * @When the Tester Passes the Mot Test
     */
    public function theTesterPassesTheMotTest()
    {
        $this->statusData = $this->motTest->passed(
            $this->sessionContext->getCurrentAccessToken(),
            $this->getMotTestNumber()
        );
    }

    /**
     * @When /^I have an MOT Test In Progress$/
     */
    public function iHaveAnMOTTestInProgress()
    {
        $this->iStartAnMotTestWithAClassVehicle(4);
    }

    /**
     * @Given /^I start an Mot Test with a Class (.*) Vehicle$/
     *
     * @param $testClass
     */
    public function iStartAnMotTestWithAClassVehicle($testClass)
    {
        $vehicleId = $this->vehicleContext->createVehicle(['testClass' => $testClass]);

        $this->motTestData = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $vehicleId,
            $testClass
        );

        PHPUnit::assertSame(200, $this->motTestData->getStatusCode());
    }

    /**
     * @When I attempt to start an Mot Test for a class :testClass vehicle
     */
    public function iAttemptToStartAnMotTestForAClassVehicle($testClass)
    {
        $vehicleId = $this->vehicleContext->createVehicle(['testClass' => $testClass]);

        $this->motTestData = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $vehicleId,
            $testClass
        );
    }

    /**
     * @When /^the Tester Aborts the Mot Test$/
     */
    public function theTesterAbortsTheMotTest()
    {
        $currentMotTestNumber = $this->motTest->getInProgressTestId($this->sessionContext->getCurrentAccessToken(), $this->sessionContext->getCurrentUserId());
        $this->statusData = $this->motTest->abort($this->sessionContext->getCurrentAccessToken(), $currentMotTestNumber);
    }

    /**
     * @Then /^the Test will not be Failed as there are no Failures$/
     */
    public function theTestWillNotBeFailedAsThereAreNoFailures()
    {
        PHPUnit::assertEquals(400, $this->statusData->getStatusCode(), 'Incorrect Status Code Returned');
        PHPUnit::assertEquals('The MOT Test does not contain failures and can not be failed', $this->statusData->getBody()['errors'][0]['message']);
    }

    /**
     * @Then /^the Test will not be Passed as there are Failures$/
     */
    public function theTestWillNotBePassedAsThereAreFailures()
    {
        PHPUnit::assertEquals(400, $this->statusData->getStatusCode(), 'Incorrect Status Code Returned');
        PHPUnit::assertEquals('The MOT Test contains failures and can not be passed', $this->statusData->getBody()['errors'][0]['message']);
    }

    /**
     * @Then /^the Test will not be Aborted as the Test is Complete$/
     */
    public function theTestWillNotBeAbortedAsTheTestIsComplete()
    {
        PHPUnit::assertEquals(403, $this->statusData->getStatusCode(), 'Incorrect Status Code Returned');
        PHPUnit::assertEquals('The MOT Test is incomplete, unable to change status to PASSED', $this->statusData->getBody()['errors'][0]['message']);
    }

    /**
     * @Then /^the Test will not Complete as it's In Progress$/
     */
    public function theTestWillNotCompleteAsItSInProgress()
    {
        PHPUnit::assertEquals(400, $this->statusData->getStatusCode(), 'Incorrect Status Code Returned');
        PHPUnit::assertEquals('The MOT Test is incomplete, unable to change status to PASSED', $this->statusData->getBody()['errors'][0]['message']);
    }

    /**
     * @Given /^the status of the test is (.*)$/
     */
    public function iHaveAMOTTest($status)
    {
        switch (strtolower($status)) {
            case 'passed':
                $this->statusData = $this->motTest->passed(
                    $this->sessionContext->getCurrentAccessToken(),
                    $this->getMotTestNumber()
                );
                break;
            case 'failed':
                $rfrId = ($this->vehicleContext->getCurrentVehicleClass() < 3) ? 356 : 8455;
                $this->reasonForRejection->addFailure($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber(), $rfrId);
                $this->statusData = $this->motTest->failed($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber());
                break;
            case 'prs':
                $rfrId = ($this->vehicleContext->getCurrentVehicleClass() < 3) ? 356 : 8455;
                $this->reasonForRejection->addPrs($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber(), $rfrId);
                $this->statusData = $this->motTest->passed($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber());
                break;
        }
    }

    /**
     * @Then /^an MOT test number should be allocated$/
     */
    public function anMOTTestNumberShouldBeAllocated()
    {
        $motTestNumber = $this->getMotTestNumber();

        PHPUnit::assertNotEmpty($motTestNumber, 'Demo MOT Test number is empty');
        PHPUnit::assertTrue(is_numeric($motTestNumber), 'Demo MOT Test number is not numeric.');
    }

    /**
     * @Then /^an MOT test number should not be allocated$/
     */
    public function anMOTTestNumberShouldNotBeAllocated()
    {
        try {
            $motTestNumber = $this->getMotTestNumber();
            PHPUnit::assertThat($motTestNumber, PHPUnit::isEmpty(), 'MOT Test number returned in response message');
        } catch (\LogicException $e) {
        }
    }

    /**
     * @Given /^I attempt to create a Demo MOT Test$/
     */
    public function iAttemptToCreateADemoMOTTest()
    {
        $this->motTestData = $this->demoTest->startMotTest($this->sessionContext->getCurrentAccessTokenOrNull());
    }

    /**
     * @Given /^I attempt to start an Mot Test with a Class (\d+) Vehicle$/
     *
     * @param $testClass
     */
    public function iAttemptToStartAnMotTestWithAClassVehicle($testClass)
    {
        $this->motTestData = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $this->vehicleContext->getCurrentVehicleId(),
            $testClass
        );
    }



    /**
     * @When /^a logged in Vehicle Examiner aborts the test$/
     */
    public function anAuthenticatedVehicleExaminerAbortsTheTest()
    {
        $user = $this->session->startSession(Authentication::LOGIN_VEHICLE_EXAMINER_USER, Authentication::PASSWORD_DEFAULT);

        $this->statusData = $this->motTest->abortTestByVE($user->getAccessToken(), $this->getMotTestNumber());
    }

    /**
     * @Given /^the Tester cancels the test with a reason of (\d+)$/
     *
     * @param $cancelReason
     */
    public function theTesterCancelsTheTestWithAReasonOf($cancelReasonId)
    {
        $this->statusData = $this->motTest->abandon($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber(), $cancelReasonId);
    }

    /**
     * @Then /^I should receive the MOT test number$/
     */
    public function iShouldReceiveTheMotTestNumber()
    {
        //Get the "In Progress" MOT Test number for the user
        $motTestNumber = $this->motTest->getInProgressTestId($this->sessionContext->getCurrentAccessToken(), $this->sessionContext->getCurrentUserId());

        PHPUnit::assertTrue(is_numeric($motTestNumber), 'MOT Test number is not a number or was not returned.');

        $this->inProgressMotTestNumber = $motTestNumber;
    }

    /**
     * @Given /^the MOT Test Number should be (\d+) digits long$/
     *
     * @param $length
     */
    public function theMOTTestNumberShouldBeDigitsLong($length)
    {
        PHPUnit::assertEquals($length, strlen($this->inProgressMotTestNumber), 'MOT Test number is not 12 digits long.');
    }

    /**
     * @Given there is a :status MOT test
     * @Given there is a :status :test MOT test
     */
    public function thereIsAMot($status, $test = 'normal')
    {
        $this->sessionContext->iAmLoggedInAsATester();

        $mot = $this->getMotTest($test);
        $this->motTestData = $mot->startMotTest($this->sessionContext->getCurrentAccessToken());

        // Set the bits so that we can pass or fail the test
        $this->odometerReading->addMeterReading($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber(), 658, 'mi');
        $this->brakeTestResult->addBrakeTestDecelerometerClass3To7($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber());

        switch($status) {
            case 'passed':
                $mot->passed($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber());
                break;
            case 'failed':
                $this->reasonForRejection->addFailure($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber());
                $mot->failed($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber());
                break;
            default:
                throw new \Exception("Unrecognised status '{$status}'");
                break;
        }
    }

    /**
     * @Then I can view the :test MOT summary
     */
    public function iCanViewTheMotSummary($test)
    {
        $mot = $this->getMotTest($test);

        $motTestData = $mot->getMotData($this->sessionContext->getCurrentAccessToken(), $mot->getLastMotTestNumber());
        PHPUnit::assertEquals(200, $motTestData->getStatusCode());
        PHPUnit::assertNotEquals('ACTIVE', $motTestData->getBody()['data']['status']);
    }

    /**
     * @When /^I start a Contingency MOT test$/
     */
    public function iStartAContingencyMOTTest()
    {
        $testClass = 4;
        $vehicleId = $this->vehicleContext->createVehicle(['testClass' => $testClass]);
        $this->contingencyTestContext->createContingencyCode();

        $emergencyLogId = $this->contingencyTestContext->getEmergencyLogId();

        $this->motTestData = $this->contingencyTest->startContingencyTest(
            $this->sessionContext->getCurrentAccessToken(),
            $emergencyLogId,
            $vehicleId,
            $testClass
        );
    }

    /**
     * @Given /^the Contingency Test is Logged$/
     */
    public function theContingencyTestIsLogged()
    {
        $actual = $this->motTest->getMotData($this->sessionContext->getCurrentAccessToken(), $this->getMotTestNumber());

        PHPUnit::assertEquals($this->contingencyTestContext->getContingencyCode(), $actual->getBody()['data']['emergencyLog']['number'], 'Contingency Code not returned.');
        PHPUnit::assertEquals($this->contingencyTestContext->getEmergencyLogId(), $actual->getBody()['data']['emergencyLog']['id'], 'Emergency Log Id not returned.');
    }

    /**
     * @return string
     */
    public function getMotTestNumber()
    {
        if (!$this->motTestData) {
            throw new \BadMethodCallException('There is no MOT test in progress');
        }

        return $this->motTestData->getBody()['data']['motTestNumber'];
    }

    /**
     * @Then /^the recorded IP is "([^"]*)"$/
     */
    public function theRecordedIPIs($clientIp)
    {
        $actualClientIp = $this->statusData->getBody()['data']['clientIp'];

        PHPUnit::assertEquals($clientIp, $actualClientIp, 'Recorded client IP incorrect');
    }

    /**
     * @When /^the Tester Passes the Mot Test from IP "([^"]*)"$/
     *
     * @param string $clientIp
     */
    public function theTesterPassesTheMotTestFromIp($clientIp)
    {
        $this->statusData = $this->motTest->passedWithIp(
            $this->sessionContext->getCurrentAccessToken(),
            $this->getMotTestNumber(),
            $clientIp
        );
    }

    /**
     * @When /^I search for a vehicle without a manufactured date and first used date$/
     *
     */
    public function ISearchForAVehicleWithoutAManufacturerAndFirstUsedDate()
    {
        $vehicleData =  [
            'dateOfFirstUse' => null,
            'manufactureDate' => null,
            'vin' => 'WF0BXXGAJB1R41234',
            'registrationNumber' => 'F50GGP'
        ];

        $this->vehicleId = $this->vehicleContext->createVehicle($vehicleData);

        // throws exception if not found
        PHPUnit::assertNotNull($this->vehicleContext->getCurrentVehicleData());
    }

    /**
     * @Then /^manufactured date and first used date should be displayed as unknown$/
     *
     */
    public function manufacturerAndFirstUsedDateShouldBeDisplayedAsNotKnown()
    {
        $data = $this->vehicleContext->getCurrentVehicleData();

        PHPUnit::assertNull($data['data']['firstUsedDate']);
        PHPUnit::assertNull($data['data']['manufactureDate']);
    }

    /**
     * @Given /^I attempt to create a MOT Test on a vehicle without a manufactured date and first used date$/
     *
     */
    public function IAttemptToCreateAnMOTTestOnAVehicleWithoutAManufacturerAndFirstUsedDate()
    {
        $vehicleData =  [
            'dateOfFirstUse' => null,
            'manufactureDate' => null,
            'vin' => 'WF0BXXGAJB1R41234',
            'registrationNumber' => 'F50GGP'
        ];

        $this->vehicleId = $this->vehicleContext->createVehicle($vehicleData);

        $this->motTestData = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $this->vehicleId
        );
    }

    /**
     * @Then /^MOT test should be created successfully$/
     *
     */
    public function MOTTestShouldBeCreatedSuccessfully()
    {
        PHPUnit::assertNotNull($this->motTestData);
    }

    /**
     * @param string $test
     * @return DemoTest|MotTest
     * @throws Exception
     */
    private function getMotTest($test)
    {
        switch ($test) {
            case 'demo':
                $mot = $this->demoTest;
                break;
            case 'normal':
                $mot = $this->motTest;
                break;
            default:
                throw new \Exception("Unrecognised test type '{$test}'");
                break;
        }
        return $mot;
    }
}
