<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Api\SpecialNotice;

class SpecialNoticeContext implements Context
{
    /**
     * @var SpecialNotice
     */
    private $specialNotice;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var Response
     */
    private $specialNoticeBroadcastResult;

    /**
     * @var Response
     */
    private $specialNoticeResponse;

    /**
     * @param SpecialNotice $specialNotice
     */
    public function __construct(SpecialNotice $specialNotice)
    {
        $this->specialNotice = $specialNotice;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @When I send a new Special Notice broadcast
     */
    public function iSendANewSpecialNoticeBroadcast()
    {
        $this->specialNoticeBroadcastResult = $this->specialNotice->sendBroadcast($this->sessionContext->getCurrentAccessToken());
    }

    /**
     * @Then I will see the broadcast was successful
     */
    public function iWillSeeTheBroadcastWasSuccessful()
    {
        PHPUnit::assertTrue($this->specialNoticeBroadcastResult);
    }

    /**
     * @When /^I create a Special Notice$/
     */
    public function iCreateASpecialNotice()
    {
        $this->specialNoticeResponse = $this->specialNotice->createSpecialNotice($this->sessionContext->getCurrentAccessTokenOrNull());
    }

    /**
     * @Then /^the Special Notice is created$/
     */
    public function theSpecialNoticeIsCreated()
    {
        PHPUnit_Framework_Assert::assertNotEmpty($this->specialNoticeResponse->getBody()['data']['id'], 'Special Notice Id was not returned in response');
        PHPUnit_Framework_Assert::assertTrue(is_int($this->specialNoticeResponse->getBody()['data']['id']), 'Special Notice Id is not a number');
        PHPUnit_Framework_Assert::assertEquals(200, $this->specialNoticeResponse->getStatusCode(), 'Incorrect Status Code returned');
    }

    /**
     * @Then /^the Special Notice is not created$/
     */
    public function theSpecialNoticeIsNotCreated()
    {
        $body = $this->specialNoticeResponse->getBody()->toArray();

        PHPUnit_Framework_Assert::assertFalse(isset($body['data']['id']), 'Special Notice Id returned in response');
        PHPUnit_Framework_Assert::assertNotEquals(200, $this->specialNoticeResponse->getStatusCode(), 'HTTP 200 Status Code returned');
    }
}
