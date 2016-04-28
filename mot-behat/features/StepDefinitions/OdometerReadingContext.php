<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\OdometerReading;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class OdometerReadingContext implements Context
{
    /**
     * @var OdometerReading
     */
    private $odometerReading;

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
    private $odometerReadingResponse;

    /**
     * @param OdometerReading $odometerReading
     */
    public function __construct(OdometerReading $odometerReading)
    {
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
     * @Given /^the Tester adds an Odometer Reading "(NO METER|NOT READ)"$/
     */
    public function theTesterAddsNoOdometerReading($type)
    {
        if ($type == 'NO METER') {
            $this->odometerReading->addNoMeterReadingToTest(
                $this->sessionContext->getCurrentAccessToken(),
                $this->motTestContext->getMotTestNumber()
            );
        } else {
            $this->odometerReading->addOdometerNotReadToTest(
                $this->sessionContext->getCurrentAccessToken(),
                $this->motTestContext->getMotTestNumber()
            );
        }
    }

    /**
     * @Given /^the Tester adds an Odometer Reading of (\d+) (mi|km)$/
     * @Given the Tester adds an Odometer Reading
     */
    public function theTesterAddsAnOdometerReadingOfMiles($value = 1000, $unit = 'km')
    {
        $response = $this->odometerReading->addMeterReading($this->sessionContext->getCurrentAccessToken(), $this->motTestContext->getMotTestNumber(), $value, $unit);

        PHPUnit::assertSame(200, $response->getStatusCode());
    }

    /**
     * @Given /^the Tester attempts to add an Odometer Reading of (?P<value>.*) (?P<unit>.*)$/
     */
    public function theTesterAttemptsToAddAnOdometerReadingOf($value, $unit)
    {
        $this->odometerReadingResponse = $this->odometerReading->addMeterReading($this->sessionContext->getCurrentAccessToken(), $this->motTestContext->getMotTestNumber(), $value, $unit);
    }

    /**
     * @Then the odometer reading is rejected
     */
    public function theOdometerReadingIsRejected()
    {
        PHPUnit::assertEquals(422, $this->odometerReadingResponse->getStatusCode(), 'Odometer reading not rejected as expected.');
    }
}
