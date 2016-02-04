<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
use Dvsa\Mot\Behat\Support\Api\RollerBrakeTest;
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

    public function getBrakeTestData($scenario){
        switch ($scenario){
            case "1":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 100,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 25,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 135,
                    "vehicleWeightRear"      => 185,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "2":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 90,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 25,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 130,
                    "vehicleWeightRear"      => 150,
                    "riderWeight"            => 120,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "3":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 100,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 0,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 135,
                    "vehicleWeightRear"      => 185,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "4":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 100,
                    "control1EffortSidecar"  => 20,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 25,
                    "control2EffortSidecar"  => 10,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 140,
                    "vehicleWeightRear"      => 140,
                    "riderWeight"            => 70,
                    "isSideCarAttached"      => 1,
                    "sidecarWeight"          => 50
                ]);
            case "5":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 40,
                    "control1EffortRear"     => 65,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 55,
                    "control2EffortRear"     => 41,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => true,
                    "control1LockRear"       => true,
                    "control2LockFront"      => true,
                    "control2LockRear"       => true,
                    "vehicleWeightFront"     => 135,
                    "vehicleWeightRear"      => 185,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "6":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 40,
                    "control1EffortRear"     => 65,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 75,
                    "control2EffortRear"     => 35,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => true,
                    "control2LockFront"      => false,
                    "control2LockRear"       => true,
                    "vehicleWeightFront"     => 135,
                    "vehicleWeightRear"      => 185,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "7":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 100,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 25,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 175,
                    "vehicleWeightRear"      => 225,
                    "riderWeight"            => null,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            case "8":
                return new RollerBrakeTest([
                    "control1EffortFront"    => 20,
                    "control1EffortRear"     => 40,
                    "control1EffortSidecar"  => 0,
                    "control2EffortFront"    => 110,
                    "control2EffortRear"     => 50,
                    "control2EffortSidecar"  => 0,
                    "control1LockFront"      => false,
                    "control1LockRear"       => false,
                    "control2LockFront"      => false,
                    "control2LockRear"       => false,
                    "vehicleWeightFront"     => 135,
                    "vehicleWeightRear"      => 185,
                    "riderWeight"            => 80,
                    "isSideCarAttached"      => 0,
                    "sidecarWeight"          => 0
                ]);
            default:
                Throw new InvalidArgumentException();
        }
    }

    /**
     * @Given /^the Tester adds a Class 1\-2 Decelerometer Brake Test with custom brake data (.*) (.*)$/
     */
    public function theTesterAddsAClass1DecelerometerBrakeTestWithCustomBrakeData($control1BrakeEfficiency, $control2BrakeEfficiency)
    {
        $param = array (
            "control1BrakeEfficiency" => $control1BrakeEfficiency,
            "control2BrakeEfficiency" => $control2BrakeEfficiency
        );

        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestDecelerometerClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $param
        );

        PHPUnit::assertEquals($this->brakeTestResultResponse->getStatusCode(), 200, 'Incorrect status code when adding Decelerometer brake test.');

    }

    /**
     * @Given /^the Tester adds a Class 1\-2 Gradient Brake Test with custom brake data (.*) (.*) (.*) (.*)$/
     */
    public function theTesterAddsAClass1GradientBrakeTestWithCustomBrakeData($control1Above30, $control2Above30, $control1Below25, $control2Below25)
    {
        $param = array (
            "control1Above30" => $control1Above30,
            "control2Above30" => $control2Above30,
            "control1Below25" => $control1Below25,
            "control2Below25" => $control2Below25
        );

        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestGradientClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $param
        );

        PHPUnit::assertEquals($this->brakeTestResultResponse->getStatusCode(), 200, 'Incorrect status code when adding Gradient brake test.');

    }

    /**
     * @Given /^I add roller brake test data for (.*)$/
     */
    public function iAddRollerBrakeTestDataFor($scenario)
    {
        $gradientBrakeTestObj = $this->getBrakeTestData($scenario);

        $param = array
        (
            "control1EffortFront"    => $gradientBrakeTestObj->getControl1EffortFront(),
            "control1EffortRear"     => $gradientBrakeTestObj->getControl1EffortRear(),
            "control1EffortSidecar"  => $gradientBrakeTestObj->getControl1EffortSidecar(),
            "control2EffortFront"    => $gradientBrakeTestObj->getControl2EffortFront(),
            "control2EffortRear"     => $gradientBrakeTestObj->getControl2EffortRear(),
            "control2EffortSidecar"  => $gradientBrakeTestObj->getControl2EffortSidecar(),
            "control1LockFront"      => $gradientBrakeTestObj->getControl1LockFront(),
            "control1LockRear"       => $gradientBrakeTestObj->getControl1LockRear(),
            "control2LockFront"      => $gradientBrakeTestObj->getControl2LockFront(),
            "control2LockRear"       => $gradientBrakeTestObj->getControl2LockRear(),
            "vehicleWeightFront"     => $gradientBrakeTestObj->getVehicleWeightFront(),
            "vehicleWeightRear"      => $gradientBrakeTestObj->getVehicleWeightRear(),
            "riderWeight"            => $gradientBrakeTestObj->RiderWeight(),
            "isSideCarAttached"      => $gradientBrakeTestObj->IsSideCarAttached(),
            "sidecarWeight"          => $gradientBrakeTestObj->SidecarWeight()
        );

        $this->brakeTestResultResponse = $this->brakeTestResult->addBrakeTestForRollerClass1To2WithCustomData(
            $this->sessionContext->getCurrentAccessToken(),
            $this->motTestContext->getMotTestNumber(),
            $param
        );

        PHPUnit::assertEquals($this->brakeTestResultResponse->getStatusCode(), 200, 'Incorrect status code when adding Roller brake test.');
    }



}
