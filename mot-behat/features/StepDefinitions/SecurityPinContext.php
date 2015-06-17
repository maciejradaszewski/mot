<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\SecurityPin;
use Dvsa\Mot\Behat\Support\Api\Session;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;

/**
 * Class ContingencyContext.
 */
class SecurityPinContext implements Context
{
    /**
     * @var SecurityPin
     */
    private $securityPin;

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
     * @var Response
     */
    private $resetPinResponse;

    /**
     * @param SecurityPin $securityPin
     * @param Session     $session
     */
    public function __construct(SecurityPin $securityPin, Session $session, TestSupportHelper $testSupportHelper)
    {
        $this->securityPin = $securityPin;
        $this->session = $session;
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @When /^I request a new security pin$/
     */
    public function iRequestANewSecurityPin()
    {
        $userId = $this->sessionContext->getCurrentUserIdOrNull();
        $accessToken = $this->sessionContext->getCurrentAccessTokenOrNull();
        $this->resetPinResponse = $this->securityPin->resetPin($userId, $accessToken);
    }

    /**
     * @Then /^I should receive a new security pin$/
     */
    public function iShouldReceiveANewSecurityPin()
    {
        $pin = $this->resetPinResponse->getBody()['data']['pin'];
        PHPUnit::assertNotEmpty($pin, 'Security pin not returned in response message.');
    }

    /**
     * @Given /^the generated pin should be a number$/
     */
    public function theGeneratedPinShouldBeANumber()
    {
        $pin = $this->resetPinResponse->getBody()['data']['pin'];
        PHPUnit::assertTrue(is_numeric($pin), 'Security pin is not a number.');
    }

    /**
     * @When /^I request a new security pin for a (.*) user$/
     */
    public function iRequestANewSecurityPinForAUser($role)
    {
        if ($role == 'ao1') {
            $areaOffice1Service = $this->testSupportHelper->getAreaOffice1Service();
            $user = $areaOffice1Service->create([]);
        } elseif ($role == 'ao2') {
            $areaOffice2Service = $this->testSupportHelper->getAreaOffice2Service();
            $user = $areaOffice2Service->create([]);
        } elseif ($role == 'tester') {
            $testerService = $this->testSupportHelper->getTesterService();
            $user = $testerService->create(['siteIds' => [1]]);
        } elseif ($role == 'csco') {
            $cscoService = $this->testSupportHelper->getCscoService();
            $user = $cscoService->create([]);
        } else {
            throw new \InvalidArgumentException('Role ' . $role . ' has not been implemented');
        }

        $user = $this->session->startSession($user->data['username'], $user->data['password']);

        $accessToken = $this->sessionContext->getCurrentAccessToken();
        $this->resetPinResponse = $this->securityPin->resetPin($user->getUserId(), $accessToken);
    }

    /**
     * @Then /^I should not receive a new security pin$/
     */
    public function iShouldNotReceiveANewSecurityPin()
    {
        PHPUnit::assertEquals($this->resetPinResponse->getBody()['errors']['message'], 'Can only reset your own PIN');
    }

    /**
     * @Given /^the generated pin should not be a number$/
     */
    public function theGeneratedPinShouldNotBeANumber()
    {
        PHPUnit::assertFalse(is_numeric($this->resetPinResponse->getBody()['data']['pin']), 'Security pin is a number.');
    }

    /**
     * @Given /^the generated pin should be (\d+) digits long$/
     * @param $length
     */
    public function theGeneratedPinShouldBeDigitsLong($length)
    {
        PHPUnit::assertEquals(200, $this->resetPinResponse->getStatusCode(), 'Did not receive 200 OK response');

        $pin = $this->resetPinResponse->getBody()['data']['pin'];
        PHPUnit::assertEquals($length, strlen($pin), 'Pin is not ' . $length . ' digits long.');
    }
}
