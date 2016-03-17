<?php

use Behat\Behat\Context\Context;
use Dvsa\Mot\Behat\Support\Api\AuthorisedExaminer;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Response;

class MotTestLogContext implements Context
{

    /**
     * @var AuthorisedExaminer
     */
    private $authorisedExaminer;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var MotTest
     */
    private $motTest;

    /**
     * @var Response
     */
    private $userTestLogs;


    public function __construct(
        AuthorisedExaminer $authorisedExaminer,
        MotTest $motTest
    ) {
        $this->authorisedExaminer = $authorisedExaminer;
        $this->motTest = $motTest;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @When I download my test logs for today
     */
    public function getTestLogs()
    {
        $this->userTestLogs = $this->authorisedExaminer->getTodaysTestLogs(
            $this->sessionContext->getCurrentAccessToken(),
            2
        );
    }

    /**
     * @When I download that site's test logs for today
     */
    public function getSiteTestLogs($siteId = 1)
    {
        $this->userTestLogs = $this->authorisedExaminer->getTodaysTestLogs(
            $this->sessionContext->getCurrentAccessToken(),
            2,
            $siteId
        );
    }

    /**
     * @Then /^I will see the correct MOT Test Log Data$/
     */
    public function iWillSeeTheCorrectMOTTestLogData()
    {
        PHPUnit::assertEquals(200, $this->userTestLogs->getStatusCode());
        $result = json_decode($this->userTestLogs->getBody()['data']['data'], true);

        foreach ($result as $motTestNumber => $motTestLogData) {
            // Retrieve the MOT test by MOT test number
            $response = $this->motTest->getMotData($this->sessionContext->getCurrentAccessToken(), $motTestNumber);
            $motTestData = $response->getBody()['data'];

            PHPUnit::assertEquals($motTestNumber, $motTestData['motTestNumber']);
            PHPUnit::assertEquals($motTestLogData['vehicleVRM'], $motTestData['registration']);
            PHPUnit::assertEquals($motTestLogData['vehicleVIN'], $motTestData['vin']);
            PHPUnit::assertEquals(
                (new \DateTime($motTestData['completedDate']))->format('dmY'),
                (new \DateTime())->format('dmY')
            );
        }
    }
}
