<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\AuthorisedExaminer;
use Dvsa\Mot\Behat\Support\Response;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use PHPUnit_Framework_Assert as PHPUnit;

class AuthorisedExaminerContext implements Context
{
    const AUTHORISED_EXAMINER_NUMBER = 'AE3412';

    /**
     * @var AuthorisedExaminer
     */
    private $authorisedExaminer;

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
    private $examinerDetailsResponse;

    /**
     * @var Response
     */
    private $removeAeResponse;

    /**
     * @var array
     */
    private $ae = [];

    /**
     * @param AuthorisedExaminer $authorisedExaminer
     * @param TestSupportHelper $testSupportHelper
     */
    public function __construct(AuthorisedExaminer $authorisedExaminer, TestSupportHelper $testSupportHelper)
    {
        $this->authorisedExaminer = $authorisedExaminer;
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
     * @When I search for an existing Authorised Examiner
     * @When I search for an existing Authorised Examiner by their number
     */
    public function iSearchForAnAuthorisedExaminer()
    {
        $this->examinerDetailsResponse = $this->authorisedExaminer->search(
            $this->sessionContext->getCurrentAccessToken(), self::AUTHORISED_EXAMINER_NUMBER
        );
    }

    /**
     * @When I attempt to obtain details of an Authorised Examiner
     */
    public function iAttemptToObtainDetailsOfAnAuthorisedExaminer()
    {
        $this->examinerDetailsResponse = $this->authorisedExaminer->getAuthorisedExaminerDetails(
            $this->sessionContext->getCurrentAccessTokenOrNull(),
            3
        );
    }

    /**
     * @Then I will not see the Authorised Examiner details
     */
    public function iWillNotSeeTheAuthorisedExaminerDetails()
    {
        PHPUnit::assertEquals(401, $this->examinerDetailsResponse->getStatusCode(), 'Incorrect status code returned.');
        PHPUnit::assertArrayNotHasKey(
            'data',
            $this->examinerDetailsResponse->getBody()->toArray(), 'Data key exists in response body.'
        );
    }

    /**
     * @When I search for an Invalid Authorised Examiner
     */
    public function iSearchForAnInvalidAuthorisedExaminer()
    {
        $this->examinerDetailsResponse = $this->authorisedExaminer->search(
            $this->sessionContext->getCurrentAccessToken(), "abcdefghijklmnopqrstuvwxyz"
        );
    }

    /**
     * @Then I will see the Authorised Examiner's details
     */
    public function theAuthorisedExaminerDetailsAreReturned()
    {
        $aeNumber = $this->examinerDetailsResponse->getBody()['data']['authorisedExaminerAuthorisation']['authorisedExaminerRef'];

        PHPUnit::assertSame($aeNumber, self::AUTHORISED_EXAMINER_NUMBER);
    }

    /**
     * @Then I am informed that Authorised Examiner does not exist
     */
    public function iAmInformedThatAuthorisedExaminerDoesNotExist()
    {
        PHPUnit::assertEquals($this->examinerDetailsResponse->getStatusCode(), 404);

        $body = $this->examinerDetailsResponse->getBody();
        PHPUnit::assertArrayHasKey('errors', $body);
        PHPUnit::assertStringEndsWith('not found', $body['errors'][0]['message']);
    }

    /**
     * @Then the Authorised Examiner record contains Data Disclosure data
     */
    public function theAuthorisedExaminerRecordContainsDataDisclosureData()
    {
        $dataDisclosure
            = $this->examinerDetailsResponse->getBody()['data']['authorisedExaminerAuthorisation']['dataDisclosure'];
        PHPUnit::assertContains($dataDisclosure, 'TODO');
    }

    /**
     * @Then I should be able to create a new Authorised Examiner
     */
    public function createNewAuthorisedExaminer()
    {
        $this->examinerDetailsResponse = $this->authorisedExaminer->createAuthorisedExaminer(
            $this->sessionContext->getCurrentAccessToken()
        );

        PHPUnit::assertEquals(200, $this->examinerDetailsResponse->getStatusCode());
    }

    /**
     * @When I attempt to remove an Authorised Examiner
     */
    public function iAttemptToRemoveAnAuthorisedExaminer()
    {
        $this->removeAeResponse = $this->authorisedExaminer->removeAuthorisedExaminer(
            $this->sessionContext->getCurrentAccessToken()
        );
    }

    /**
     * @Then the attempt will be forbidden
     */
    public function theAttemptWillBeForbidden()
    {
        PHPUnit::assertThat($this->removeAeResponse->getStatusCode(), PHPUnit::equalTo(403));
    }

    /**
     * @Then /^I should be able to approve this Authorised Examiner$/
     */
    public function approveAnAuthorisedExaminer()
    {
        $authorisedExaminerResponse = $this->authorisedExaminer->updateStatusAuthorisedExaminer(
            $this->sessionContext->getCurrentAccessToken(),
            $this->examinerDetailsResponse->getBody()['data']['id'],
            \DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode::APPROVED
        );

        PHPUnit::assertEquals(200, $authorisedExaminerResponse->getStatusCode());
    }

    /**
     * @return array
     */
    public function createAE()
    {
        if (!empty($this->ae)) {
            return $this->ae;
        }

        $data = [
            "slots" => 1001,
            "requestor" => [
                "username" => "areaoffice1user",
                "password" => "Password1"
            ]
        ];

        $response = $this->testSupportHelper->getAeService()->create($data);

        $this->ae = [
            "id" => $response->data["id"],
            "aeRef" => $response->data["aeRef"],
            "aeName" => $response->data["aeName"],
        ];

        return $this->ae;
    }

    /**
     * @return array
     */
    public function getAE()
    {
        return $this->ae;
    }
}
