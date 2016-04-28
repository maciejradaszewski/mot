<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
use Dvsa\Mot\Behat\Support\Api\RollerBrakeTestClass3To7;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class BrakeTestResultContext implements Context
{
    /**
     * @var BrakeTestResult
     */
    private $brakeTestResult;

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
    private $brakeTestResultResponse;

    /**
     * @param BrakeTestResult $brakeTestResult
     */
    public function __construct(BrakeTestResult $brakeTestResult)
    {
        $this->brakeTestResult = $brakeTestResult;
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
     * @Given the Tester adds a Class 3-7 Roller Brake Test Result
     */
    public function theTesterAddsAClass3To7RollerBrakeTestResult()
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestRollerClass3To7(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber()
        );
    }

    /**
     * @Given the Tester adds a Class 1-2 Roller Brake Test Result
     */
    public function theTesterAddsAClass1To2RollerBrakeTestResult()
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestForRollerClass1To2(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber()
        );
    }

    /**
     * @Given the Tester adds a Class 3-7 Decelerometer Brake Test
     */
    public function theTesterAddsAClass3To7DecelerometerBrakeTest()
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass3To7(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber()
        );

        PHPUnit::assertEquals(200, $this->brakeTestResultResponse->getStatusCode(), 'Confirm Deceleromter was added');
    }

    /**
     * @Given /^the Tester adds a Class 3-7 Decelerometer Brake Test Result with custom (.*)$/
     */
    public function theTesterAddsAClass3To7DecelerometerBrakeTestWithCustomData($scenario)
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass3To7WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $this->getDecelerometerBrakeTestDataForClass3To7($scenario)
        );
    }

    /**
     * @Given the Tester adds a Class 1-2 Decelerometer Brake Test
     */
    public function theTesterAddsAClass1DecelerometerBrakeTest()
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass1To2(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber()
        );

        PHPUnit::assertEquals(200, $this->brakeTestResultResponse->getStatusCode(), 'Incorrect status code when adding Decelerometer brake test.');
    }

    /**
     * @Given /^the Tester adds a Class 1-2 Decelerometer Brake Test with custom brake data (.*)$/
     */
    public function theTesterAddsAClass1DecelerometerBrakeTestWithCustomBrakeData($scenario)
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $this->getDecelerometerBrakeTestDataForClass1To2($scenario)
        );
    }

    /**
     * @Given /^I add roller brake test data for (.*)$/
     */
    public function iAddRollerBrakeTestDataFor($scenario)
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestForRollerClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $this->getRollerBrakeTestObjectForClass1And2($scenario)
        );
    }

    /**
     * @Given /^I add gradient brake test data for (.*)$/
     */
    public function iAddGradientBrakeTestDataFor($scenario)
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestGradientClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $this->getGradientBrakeTestObjectForClass1And2($scenario)
        );

        PHPUnit::assertEquals($this->brakeTestResultResponse->getStatusCode(), 200, 'Incorrect status code when adding Gradient brake test.');
    }

    /**
     * @Given /^I add floor brake test data for (.*)$/
     */
    public function iAddFloorBrakeTestDataFor($scenario)
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestFloorClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $this->getFloorBrakeTestObjectForClass1And2($scenario)
        );
    }

    /**
     * @When the Tester adds a Class 3-7 Plate Brake Test
     */
    public function theTesterAddsAClass3to7PlateBrakeTest()
    {
        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestPlateClass3to7(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber()
        );
    }

    /**
     * @Given /^the Tester adds a Class 3-7 Roller Brake Test Result with custom (.*)$/
     */
    public function theTesterAddsAClass3RollerBrakeTestResultWithCustom($scenario)
    {
        $rollerBrakeTestObject = $this->getRollerBrakeTestObjectForClass3To7($scenario);

        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestRollerClass3To7WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $rollerBrakeTestObject
        );
    }

    private function getRollerBrakeTestObjectForClass3To7($scenario) {
        switch ($scenario) {
            case "class4.roller.valid.high":
                return new RollerBrakeTestClass3To7([
                    'serviceBrake1Data' => [
                        'effortNearsideAxle1' => 9999,
                        'effortOffsideAxle1' => 9999,
                        'lockNearsideAxle1' => false,
                        'lockOffsideAxle1' => true,
                        'effortNearsideAxle2' => 9999,
                        'effortOffsideAxle2' => 9999,
                        'lockNearsideAxle2' => true,
                        'lockOffsideAxle2' => false,
                    ],
                    'parkingBrakeEffortSingle' => 9999,
                    'parkingBrakeLockSingle' => false,
                    'parkingBrakeEffortNearside' => 9999,
                    'parkingBrakeEffortOffside' => 9999,
                    'parkingBrakeLockNearside' => false,
                    'parkingBrakeLockOffside' => false,
                    'serviceBrake1TestType' => 'rollr',
                    'serviceBrake2TestType' => null,
                    'parkingBrakeTestType' => 'rollr',
                    'weightType' => 'vsi',
                    'vehicleWeight' => 1000,
                    'brakeLineType' => 'dual',
                    'numberOfAxles' => 2,
                    'parkingBrakeNumberOfAxles' => 1,
                    'positionOfSingleWheel' => null,
                    'parkingBrakeWheelsCount' => null,
                    'serviceBrakeControlsCount' => null,
                    'vehiclePurposeType' => null,
                    'serviceBrakeIsSingleLine' => true,
                    'weightIsUnladen' => false,
                    'isCommercialVehicle' => false,
                ]);
            case "class4.roller.valid.low":
                return new RollerBrakeTestClass3To7([
                    'serviceBrake1Data' => [
                        'effortNearsideAxle1' => 23,
                        'effortOffsideAxle1' => 23,
                        'lockNearsideAxle1' => true,
                        'lockOffsideAxle1' => true,
                        'effortNearsideAxle2' => 23,
                        'effortOffsideAxle2' => 23,
                        'lockNearsideAxle2' => true,
                        'lockOffsideAxle2' => true,
                    ],
                    'parkingBrakeEffortSingle' => 23,
                    'parkingBrakeLockSingle' => true,
                    'parkingBrakeEffortNearside' => 23,
                    'parkingBrakeEffortOffside' => 23,
                    'parkingBrakeLockNearside' => true,
                    'parkingBrakeLockOffside' => true,
                    'serviceBrake1TestType' => 'rollr',
                    'serviceBrake2TestType' => null,
                    'parkingBrakeTestType' => 'rollr',
                    'weightType' => 'vsi',
                    'vehicleWeight' => 1000,
                    'brakeLineType' => 'dual',
                    'numberOfAxles' => 2,
                    'parkingBrakeNumberOfAxles' => 1,
                    'positionOfSingleWheel' => null,
                    'parkingBrakeWheelsCount' => null,
                    'serviceBrakeControlsCount' => null,
                    'vehiclePurposeType' => null,
                    'serviceBrakeIsSingleLine' => true,
                    'weightIsUnladen' => false,
                    'isCommercialVehicle' => false,
                ]);
            case "class4.roller.invalid.low":
                return new RollerBrakeTestClass3To7([
                    'serviceBrake1Data' => [
                        'effortNearsideAxle1' => 0,
                        'effortOffsideAxle1' => 0,
                        'lockNearsideAxle1' => true,
                        'lockOffsideAxle1' => true,
                        'effortNearsideAxle2' => 0,
                        'effortOffsideAxle2' => 0,
                        'lockNearsideAxle2' => true,
                        'lockOffsideAxle2' => true,
                    ],
                    'parkingBrakeEffortSingle' => 0,
                    'parkingBrakeLockSingle' => false,
                    'parkingBrakeEffortNearside' => 0,
                    'parkingBrakeEffortOffside' => 0,
                    'parkingBrakeLockNearside' => false,
                    'parkingBrakeLockOffside' => false,
                    'serviceBrake1TestType' => 'rollr',
                    'serviceBrake2TestType' => null,
                    'parkingBrakeTestType' => 'rollr',
                    'weightType' => 'vsi',
                    'vehicleWeight' => 1000,
                    'brakeLineType' => 'dual',
                    'numberOfAxles' => 2,
                    'parkingBrakeNumberOfAxles' => 1,
                    'positionOfSingleWheel' => null,
                    'parkingBrakeWheelsCount' => null,
                    'serviceBrakeControlsCount' => null,
                    'vehiclePurposeType' => null,
                    'serviceBrakeIsSingleLine' => false,
                    'weightIsUnladen' => false,
                    'isCommercialVehicle' => false,
                ]);
            case "class4.roller.invalid.high":
                return new RollerBrakeTestClass3To7([
                    'serviceBrake1Data' => [
                        'effortNearsideAxle1' => 10000,
                        'effortOffsideAxle1' => 10000,
                        'lockNearsideAxle1' => true,
                        'lockOffsideAxle1' => true,
                        'effortNearsideAxle2' => 10000,
                        'effortOffsideAxle2' => 10000,
                        'lockNearsideAxle2' => true,
                        'lockOffsideAxle2' => true,
                    ],
                    'parkingBrakeEffortSingle' => 10000,
                    'parkingBrakeLockSingle' => false,
                    'parkingBrakeEffortNearside' => 10000,
                    'parkingBrakeEffortOffside' => 10000,
                    'parkingBrakeLockNearside' => false,
                    'parkingBrakeLockOffside' => false,
                    'serviceBrake1TestType' => 'rollr',
                    'serviceBrake2TestType' => null,
                    'parkingBrakeTestType' => 'rollr',
                    'weightType' => 'vsi',
                    'vehicleWeight' => 1000,
                    'brakeLineType' => 'dual',
                    'numberOfAxles' => 2,
                    'parkingBrakeNumberOfAxles' => 1,
                    'positionOfSingleWheel' => null,
                    'parkingBrakeWheelsCount' => null,
                    'serviceBrakeControlsCount' => null,
                    'vehiclePurposeType' => null,
                    'serviceBrakeIsSingleLine' => false,
                    'weightIsUnladen' => false,
                    'isCommercialVehicle' => false,
                ]);
            default:
                throw new InvalidArgumentException;
        }
    }

    private function getRollerBrakeTestObjectForClass1And2($scenario){
        switch ($scenario){
            case "class1.roller.valid.high":
                return [
                    "control1EffortFront"    => 9999,
                    "control1EffortRear"     => 9999,
                    "control1EffortSidecar"  => 9999,
                    "control2EffortFront"    => 9999,
                    "control2EffortRear"     => 9999,
                    "control2EffortSidecar"  => 9999,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 9999,
                    "vehicleWeightRear"      => 9999,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ];
            case "class1.roller.valid.low":
                return [
                    "control1EffortFront"    => 23,
                    "control1EffortRear"     => 23,
                    "control1EffortSidecar"  => 23,
                    "control2EffortFront"    => 23,
                    "control2EffortRear"     => 23,
                    "control2EffortSidecar"  => 23,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 23,
                    "vehicleWeightRear"      => 23,
                    "riderWeight"            => 23,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ];
            case "class1.roller.invalid.high":
                return [
                    "control1EffortFront"    => 10000,
                    "control1EffortRear"     => 10000,
                    "control1EffortSidecar"  => 10000,
                    "control2EffortFront"    => 10000,
                    "control2EffortRear"     => 10000,
                    "control2EffortSidecar"  => 10000,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 10000,
                    "vehicleWeightRear"      => 10000,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ];
            case "class1.roller.invalid.low":
                return [
                    "control1EffortFront"    => -1,
                    "control1EffortRear"     => -1,
                    "control1EffortSidecar"  => -1,
                    "control2EffortFront"    => 0,
                    "control2EffortRear"     => 0,
                    "control2EffortSidecar"  => -1,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 0,
                    "vehicleWeightRear"      => 10,
                    "riderWeight"            => 70,
                    "isSideCarAttached"      => 1,
                    "sidecarWeight"          => 50
                ];
            default:
                Throw new InvalidArgumentException();
        }
    }

    private function getFloorBrakeTestObjectForClass1And2($scenario)
    {
        switch ($scenario) {
            case "class1.floor.valid.high":
                return [
                    "control1Effort" => 9999,
                    "control2Effort" => 9999,
                    "vehicleWeightFront" => 9999,
                    "vehicleWeightRear" => 9999,
                    "riderWeight" => 80,
                    "sidecarWeight" => 0
                ];
            case "class1.floor.valid.low":
                return [
                    "control1Effort" => 1,
                    "control2Effort" => 1,
                    "vehicleWeightFront" => 1,
                    "vehicleWeightRear" => 1,
                    "riderWeight" => 1,
                    "sidecarWeight" => 0
                ];
            case "class1.floor.invalid.high":
                return [
                    "control1Effort" => 10000,
                    "control2Effort" => 10000,
                    "vehicleWeightFront" => 10000,
                    "vehicleWeightRear" => 10000,
                    "riderWeight" => 10000,
                    "sidecarWeight" => 10000
                ];
            case "class1.floor.invalid.low":
                return [
                    "control1Effort" => 0,
                    "control2Effort" => 0,
                    "vehicleWeightFront" => 0,
                    "vehicleWeightRear" => 0,
                    "riderWeight" => 0,
                    "sidecarWeight" => 0
                ];
            default:
                throw new InvalidArgumentException;
        }
    }

    private function getGradientBrakeTestObjectForClass1And2($scenario){
        switch ($scenario){
            case "class1.gradient.valid.high":
                return [
                    "control1Above30" => true,
                    "control2Above30" => true,
                    "control1Below25" => false,
                    "control2Below25" => false
                ];
            case "class1.gradient.valid.low":
                return [
                    "control1Above30" => true,
                    "control2Above30" => false,
                    "control1Below25" => false,
                    "control2Below25" => false
                ];
            case "class1.gradient.invalid.high":
                return [
                    "control1Above30" => false,
                    "control2Above30" => false,
                    "control1Below25" => true,
                    "control2Below25" => true
                ];
            case "class1.gradient.invalid.low":
                return [
                    "control1Above30" => false,
                    "control2Above30" => false,
                    "control1Below25" => false,
                    "control2Below25" => false
                ];
            default:
                throw new InvalidArgumentException;
        }
    }

    private function getDecelerometerBrakeTestDataForClass1To2($scenario)
    {
        switch ($scenario) {
            case "class1.decelerometer.valid.high":
                return [
                    'control1BrakeEfficiency' => 65535,
                    'control2BrakeEfficiency' => 65535
                ];
            case "class1.decelerometer.valid.low":
                return [
                    'control1BrakeEfficiency' => 30,
                    'control2BrakeEfficiency' => 30
                ];
            case "class1.decelerometer.invalid.high":
                return [
                    'control1BrakeEfficiency' => 65536,
                    'control2BrakeEfficiency' => 65536
                ];
            case "class1.decelerometer.invalid.low":
                return [
                    'control1BrakeEfficiency' => 29,
                    'control2BrakeEfficiency' => 29
                ];
        }
    }

    private function getDecelerometerBrakeTestDataForClass3To7($scenario)
    {
        switch ($scenario) {
            case "class4.decelerometer.valid.high":
                return [
                    "serviceBrake1Efficiency" => 100,
                    "parkingBrakeEfficiency" => 100,
                    "isCommercialVehicle" => false
                ];
            case "class4.decelerometer.valid.low":
                return [
                    "serviceBrake1Efficiency" => 50,
                    "parkingBrakeEfficiency" => 16,
                    "isCommercialVehicle" => false
                ];
            case "class4.decelerometer.invalid.high":
                return [
                    "serviceBrake1Efficiency" => 101,
                    "parkingBrakeEfficiency" => 101,
                    "isCommercialVehicle" => false
                ];
            case "class4.decelerometer.invalid.low":
                return [
                    "serviceBrake1Efficiency" => 49,
                    "parkingBrakeEfficiency" => 15,
                    "isCommercialVehicle" => false
                ];
            case "class4.decelerometer.commercial.valid.high":
                return [
                    "serviceBrake1Efficiency" => 58,
                    "parkingBrakeEfficiency" => 30,
                    "isCommercialVehicle" => true
                ];
            case "class4.decelerometer.commercial.valid.low":
                return [
                    "serviceBrake1Efficiency" => 57,
                    "parkingBrakeEfficiency" => 30,
                    "isCommercialVehicle" => true
                ];
            case "class4.decelerometer.goods.valid.high":
                return [
                    "serviceBrake1Efficiency" => 50,
                    "parkingBrakeEfficiency" => 30,
                    "isCommercialVehicle" => false
                ];
            case "class4.decelerometer.goods.invalid.low":
                return [
                    "serviceBrake1Efficiency" => 49,
                    "parkingBrakeEfficiency" => 30,
                    "isCommercialVehicle" => false
                ];
            default:
                throw new InvalidArgumentException;
        }
    }

}
