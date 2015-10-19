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
    private $motTestContext;
    private $createdVehicle;
    private $motTestId;
    private $vehicleData;

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
        $this->motTestContext = $scope->getEnvironment()->getContext(MotTestContext::class);
    }

    /**
     * @Given I have imported a vehicle with registration :registration and vin :vin from DVLA
     *
     * @param string $registration
     * @param string $vin
     */
    public function iHaveImportedAVehicleWithRegistrationAndVinFromDvla($registration, $vin)
    {
        $dvlaVehicleService = $this->testSupportHelper->getDVLAVehicleService();

        $this->vehicleData = [
            'registration' => $registration,
            'vin' => $vin
        ];

        $dvlaVehicleService->save($this->vehicleData);
    }

    /**
     * @Given I have completed an MOT test on the vehicle
     */
    public function iHaveCompletedAnMotTestOnAVehicleWithRegistrationAndVin()
    {
        $dvlaVehicleService = $this->testSupportHelper->getDVLAVehicleService();
        $vehicleService = $this->testSupportHelper->getVehicleService();

        // get the vehicle
        $dvlaVehicle = (int)$this->vehicle->vehicleSearch(
            $this->sessionContext->getCurrentAccessToken(),
            $this->vehicleData['registration'],
            $this->vehicleData['vin'],
            $searchDvla = true
        )->getBody()['data']['vehicle']['id'];

        // add to the vehicle table
        $this->createdVehicle = $vehicleService->createWithDefaults($this->vehicleData);

        // update dvla_vehicle entry with vehicleId of entry in vehicle table
        $dvlaVehicleService->update($dvlaVehicle, ['vehicle_id' => $this->createdVehicle]);

        // MOT the vehicle
        $this->motTestId = $this->motTest->startNewMotTestWithVehicleId(
            $this->sessionContext->getCurrentAccessToken(),
            $this->sessionContext->getCurrentUserId(),
            $this->createdVehicle
        )->getBody()['data']['motTestNumber'];

        $response = $this->odometerReading->addMeterReading(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestId,
            1000,
            'mi'
        );

        PHPUnit::assertSame(200, $response->getStatusCode());

        $brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass3To7(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestId
        );

        PHPUnit::assertEquals(200, $brakeTestResultResponse->getStatusCode());

        // pass the MOT
        $this->statusData = $this->motTest->passed(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestId
        );
    }

    /**
     * @When I update the vehicle to a new registration of :newRegistration
     */
    public function iUpdateTheVehicleWithRegistrationAndVinToANewRegistrationOf($newRegistration)
    {
        $dvlaVehicleService = $this->testSupportHelper->getDVLAVehicleService();
        $vehicleService = $this->testSupportHelper->getVehicleService();
        $motService = $this->testSupportHelper->getMotService();

        $dvlaVehicleId = $dvlaVehicleService->fetchId(
            $this->vehicleData['registration'],
            $this->vehicleData['vin']
        );

        // update the dvla_vehicle entry
        $dvlaVehicleService->update($dvlaVehicleId, ['registration' => $newRegistration]);

        // update vehicle entry with new dvla_vehicle entry details
        $vehicleService->update($this->createdVehicle, ['registration' => $newRegistration]);

        // update latest MOT test with new dvla_vehicle entry details
        $motService->updateLatest($this->createdVehicle, ['registration' => $newRegistration]);
    }

    /**
     * @When I create a cherished transfer replacement MOT certificate
     */
    public function createCherishedTransferReplacementCertificate()
    {
        // call update API endpoint to generate certificate replacement
        $this->vehicle->dvlaVehicleUpdated(
            $this->sessionContext->getCurrentAccessToken(),
            $this->createdVehicle
        );
    }

    /**
     * @When I attempt to create a cherished transfer replacement MOT certificate
     */
    public function attemptToCreateACherishedTransferReplacementCertificate()
    {
        $vehicleId = $this->completeMotTest();

        // this doesn't generate a replacement certificate, figure out why
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
     * @Then a replacement certificate of type :type is created
     *
     * @param string $type
     */
    public function aReplacementCertificateOfTypeIsCreated($type)
    {
        if ($type == 'Transfer') {
            $certificateCode = 'F';
        } elseif ($type == 'Replacement') {
            $certificateCode = 'R';
        }

        $certificateReplacementService = $this->testSupportHelper->getCertificateReplacementService();
        $motTestService = $this->testSupportHelper->getMotService();

        $motTest = $motTestService->getLatestTest($this->createdVehicle);

        // get the certificate replacement
        $certificateType = $certificateReplacementService->getCertificateReplacementType($motTest);

        PHPUnit::assertEquals($certificateType, $certificateCode);
    }

    /**
     * Create a vehicle, complete an MOT test for it and return it's id.
     *
     * @return string
     */
    private function completeMotTest()
    {
        $testerService = $this->testSupportHelper->getTesterService();
        $tester = $testerService->create(
            ['siteIds' => [1],]
        );

        $testerSession = $this->session->startSession($tester->data['username'], $tester->data['password']);
        $testerToken = $testerSession->getAccessToken();

        // @todo The vehicle service should not have "dual-mode" and returnOriginalId will be removed
        $vehicleData = ['testClass' => 4, 'returnOriginalId' => true];
        $motTest = $this->vehicle->create($testerToken, $vehicleData);

        $vehicleId = $motTest->getBody()['data']['vehicleId'];
        $motTestId = $motTest->getBody()['data']['startedMotTestNumber'];

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
