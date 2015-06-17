<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\Api\ContingencyTest;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class ContingencyTestContext implements Context
{
    /**
     * @var ContingencyTest
     */
    private $contingencyTest;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var Response
     */
    private $createContingencyCodeIdResponse;

    /**
     * @var array
     */
    private $contingencyData;

    /**
     * @param ContingencyTest $contingencyTest
     */
    public function __construct(ContingencyTest $contingencyTest)
    {
        $this->contingencyTest = $contingencyTest;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @When /^I create a new (.*) contingency test$/
     */
    public function iCreateANewContingencyTest($testType)
    {
        $this->createContingencyCode($testType, '12345A', 'PI');
    }

    /**
     * @When /^I attempt to create a new (.*) contingency test$/
     */
    public function iAttemptToCreateANewContingencyTest($testType)
    {
        $this->createContingencyCode($testType, '12345A', 'PI');
    }

    /**
     * @When /^I attempt to create a new (normal|retest) contingency test with a (.*)$/
     */
    public function iAttemptToCreateANewContingencyTestWithA($testType, $contingencyCode)
    {
        $this->createContingencyCode($testType, $contingencyCode, 'PI');
    }

    /**
     * @When /^I create a new (.*) contingency test with a Contingency Code and Reason (.*) (.*)$/
     */
    public function iCreateANewContingencyTestWithAContingencyCodeAndReason($testType, $contingencyCode, $reason)
    {
        $this->createContingencyCode($testType, $contingencyCode, $reason);
    }

    /**
     * @When /^I create a new (.*) contingency test with reason (.*)$/
     */
    public function iCreateANewContingencyTestWithReason($testType, $reason)
    {
        $this->createContingencyCode($testType, Authentication::CONTINGENCY_CODE_DEFAULT, $reason);
    }

    /**
     * @Then /^I should receive an emergency log id$/
     */
    public function iShouldReceiveAnEmergencyLogId()
    {
        PHPUnit::assertTrue(is_int($this->getEmergencyLogId()), 'Emergency log Id is not a number.');
    }

    /**
     * @param string $testType
     * @param string $contingencyCode
     * @param string $reasonCode
     */
    public function createContingencyCode($testType = 'normal', $contingencyCode = Authentication::CONTINGENCY_CODE_DEFAULT, $reasonCode = 'PI')
    {
        $this->contingencyData = [
            'testType' => $testType,
            'contingencyCode' => $contingencyCode,
            'reasonCode' => $reasonCode,
        ];

        $this->createContingencyCodeIdResponse = $this->contingencyTest->getContingencyCodeID(
            $this->sessionContext->getCurrentAccessTokenOrNull(),
            $this->contingencyData['testType'],
            $this->contingencyData['contingencyCode'],
            $this->contingencyData['reasonCode']
        );
    }

    /**
     * @return string
     */
    public function getContingencyCode()
    {
        if (!isset($this->contingencyData['contingencyCode'])) {
            throw new \LogicException('No contingency code was set');
        }

        return (string) $this->contingencyData['contingencyCode'];
    }

    /**
     * @return int
     */
    public function getEmergencyLogId()
    {
        if (200 !== $this->createContingencyCodeIdResponse->getStatusCode()) {
            throw new \LogicException('Failed to get the contingency code');
        }

        return (int) $this->createContingencyCodeIdResponse->getBody()['data']['emergencyLogId'];
    }
}