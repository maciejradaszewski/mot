<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class AmendBrakeTestResultsContext implements Context
{
    private $sessionContext;
    private $vehicleContext;
    private $motTest;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->vehicleContext = $scope->getEnvironment()->getContext(VehicleContext::class);
    }

    /**
     * @param
     */
    public function __construct(\Dvsa\Mot\Behat\Support\Api\MotTest $motTest)
    {
        $this->motTest = $motTest;
    }


    /**
     * @When /^I amend brake test results$/
     */
    public function iAmendBrakeTestResults()
    {

    }


}

?>