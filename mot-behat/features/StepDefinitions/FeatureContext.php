<?php

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\History;
use PHPUnit_Framework_Assert as PHPUnit;

class FeatureContext implements Context
{
    /**
     * @var History
     */
    private $history;

    /**
     * @param History $history
     */
    public function __construct(History $history)
    {
        $this->history = $history;
    }

    /**
     * @AfterStep
     */
    public function debug(AfterStepScope $scope)
    {
        if ($scope->getTestResult()->isPassed() || $scope->getTestResult()->getResultCode() == 2) {
            return;
        }

        foreach ($this->history->getAllResponses() as $response) {
            echo "> > >\n";
            echo preg_replace('/^/sm', '      ', $response->getRequest())."\n";
            echo "< < <\n";
            echo preg_replace('/^/sm', '      ', $response)."\n";
        }
    }

    /**
     * @AfterScenario
     */
    public function clearHistory(AfterScenarioScope $scope)
    {
        $this->history->clear();
    }

    /**
     * @BeforeScenario
     */
    public function cleanupContexts(BeforeScenarioScope $scope)
    {
        (new ContextCleanup())->cleanup($scope->getEnvironment()->getContexts());
    }

    /**
     * @Then /^I should receive an Unauthorised response$/
     *
     * @deprecated this is only temporarly here and will be removed as soon as scenarios are reworded to not include implementation details
     */
    public function iShouldReceiveAnUnauthorisedResponse()
    {
        $response = $this->history->getLastResponse();

        PHPUnit::assertEquals(401, $response->getStatusCode(), 'Did not receive 401 Unauthorised response');
    }

    /**
     * @Given /^I should not see any data in the response body$/
     *
     * @deprecated this is only temporarly here and will be removed as soon as scenarios are reworded to not include implementation details
     */
    public function iShouldNotSeeAnyDataInTheResponseBody()
    {
        $response = $this->history->getLastResponse();

        PHPUnit::assertFalse(isset($response->getBody()['data']), 'Data key found in response body');
    }

    /**
     * @Then /^I should receive a Forbidden response$/
     *
     * @deprecated this is only temporarly here and will be removed as soon as scenarios are reworded to not include implementation details
     */
    public function iShouldReceiveAForbiddenResponse()
    {
        $response = $this->history->getLastResponse();

        PHPUnit::assertEquals(403, $response->getStatusCode(), 'Did not receive 403 Forbidden response');
    }

    /**
     * @Then /^I should receive a Bad Request response$/
     *
     * @deprecated this is only temporarly here and will be removed as soon as scenarios are reworded to not include implementation details
     */
    public function iShouldReceiveABadRequestResponse()
    {
        $response = $this->history->getLastResponse();

        PHPUnit::assertEquals(400, $response->getStatusCode(), 'Did not receive 400 Bad Request response');
    }
}
