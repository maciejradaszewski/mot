<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
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
        $this->brakeTestResultResponse = $this->breakTestResult->addBrakeTestForRollerClass1To2(
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
}
