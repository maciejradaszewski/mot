<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;

class MotTestSearchContext implements Context
{
    const SITE_NUMBER = 'V1234';

    /**
     * @var MotTest
     */
    private $motTest;

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
    private $searchResponse;

    /**
     * @param MotTest $motTest
     */
    public function __construct(MotTest $motTest)
    {
        $this->motTest = $motTest;
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
     * @When I search for an MOT test
     */
    public function iSearchForAnMOTTest()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['siteNr' => self::SITE_NUMBER]
        );
    }

    /**
     * @When I search for an Invalid MOT test
     */
    public function iSearchForAnInvalidMOTTest()
    {
        $this->searchResponse = $this->motTest->searchMOTTest(
            $this->sessionContext->getCurrentAccessToken(),
            ['siteNr' => 'abcdefghijklmnopqrstuvwxyz']
        );
    }

    /**
     * @Then the MOT test data is returned
     */
    public function theMOTTestDataIsReturned()
    {
        $motTestNumber = $this->motTestContext->getMotTestNumber();
        $data = $this->searchResponse->getBody()['data']['data'];
        PHPUnit::assertArrayHasKey($motTestNumber, $data);
    }

    /**
     * @Then the MOT test is not found
     */
    public function theMOTTestIsNotFound()
    {
        $body = $this->searchResponse->getBody()->toArray();
        PHPUnit::assertEmpty($body['data']['data']);
    }
}
