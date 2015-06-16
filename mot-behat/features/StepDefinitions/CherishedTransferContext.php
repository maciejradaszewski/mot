<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Vehicle;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
use Dvsa\Mot\Behat\Support\Api\OdometerReading;
use PHPUnit_Framework_Assert as PHPUnit;

class CherishedTransferContext implements Context
{
    private $session;
    private $vehicle;
    private $motTest;
    private $testSupportHelper;
    private $brakeTestResult;
    private $odometerReading;
    private $sessionContext;

    /**
     * @param Session $session
     * @param Vehicle $vehicle
     * @param MotTest $motTest
     * @param TestSupportHelper $testSupportHelper
     * @param BrakeTestResult $brakeTestResult
     * @param OdometerReading $odometerReading
     */
    public function __construct(
        Session $session,
        Vehicle $vehicle,
        MotTest $motTest,
        TestSupportHelper $testSupportHelper,
        BrakeTestResult $brakeTestResult,
        OdometerReading $odometerReading
    ) {
        $this->session = $session;
        $this->vehicle = $vehicle;
        $this->motTest = $motTest;
        $this->testSupportHelper = $testSupportHelper;
        $this->brakeTestResult = $brakeTestResult;
        $this->odometerReading = $odometerReading;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @When I attempt to create a cherished transfer replacement MOT certificate
     */
    public function attemptToCreateACherishedTransferReplacementCertificate()
    {
        $vehicleId = $this->completeMotTest();

        $this->response = $this->vehicle->dvlaVehicleUpdated(
            $this->sessionContext->getCurrentAccessToken(),
            $vehicleId
        );
    }

    /**
     * @Then a replacement certificate will be created
     */
    public function aReplacementCertificateWillBeCreated()
    {
        PHPUnit::assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @Then I would be forbidden to create replacement
     */
    public function iWouldBeForbiddenToCreateReplacement()
    {
        PHPUnit::assertEquals(403, $this->response->getStatusCode());
    }

    /**
     * @return string
     */
    private function completeMotTest()
    {
        $testerService = $this->testSupportHelper->getTesterService();
        $tester = $testerService->create([
            'siteIds' => [1],
        ]);

        $testerSession = $this->session->startSession($tester->data['username'], $tester->data['password']);
        $testerToken = $testerSession->getAccessToken();

        // @todo The vehicle service should not have "dual-mode" and returnOriginalId will be removed
        $vehicleData = ['testClass' => 4, 'returnOriginalId' => true];
        $createdVehicle = $this->vehicle->create($testerToken, $vehicleData);

        $vehicleId = $createdVehicle->getBody()['data']['vehicleId'];
        $motTestId = $createdVehicle->getBody()['data']['startedMotTestNumber'];

        $this->odometerReading->addMeterReading(
            $testerToken,
            $motTestId,
            1000,
            'mi'
        );

        $this->brakeTestResult->addBrakeTestDecelerometerClass3To7(
            $testerToken,
            $motTestId
        );

        $this->motTest->passed(
            $testerToken,
            $motTestId
        );

        return $vehicleId;
    }
}
