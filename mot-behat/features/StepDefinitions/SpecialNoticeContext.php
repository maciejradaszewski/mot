<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Dvsa\Mot\Behat\Support\Response;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Api\SpecialNotice;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use Dvsa\Mot\Behat\Datasource\Authentication;

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
     * @var VtsContext
     */
    private $vtsContext;

    /**
     * @var PersonContext
     */
    private $personContext;

    /**
     * @var Response
     */
    private $specialNoticeBroadcastResult;

    /**
     * @var Response
     */
    private $specialNoticeResponse;

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /**
     * @var Session
     */
    private $session;

    private $tester;

    private $areaOffice1user;

    /**
     * @param SpecialNotice $specialNotice
     */
    public function __construct(
        SpecialNotice $specialNotice,
        TestSupportHelper $testSupportHelper,
        Session $session
    )
    {
        $this->specialNotice = $specialNotice;
        $this->testSupportHelper = $testSupportHelper;
        $this->session = $session;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->vtsContext = $scope->getEnvironment()->getContext(VtsContext::class);
        $this->personContext = $scope->getEnvironment()->getContext(PersonContext::class);
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
     * @When I create a Special Notice with data:
     */
    public function iCreateASpecialNoticeWithData(TableNode $table)
    {
        $hash = $table->getColumnsHash();

        if (count($hash) !== 1) {
            throw new \InvalidArgumentException(sprintf('Expected a single record but got: %d', count($hash)));
        }

        $data = $hash[0];

        $roles = array_map(function ($role) {
            return trim($role);
        }, explode(",", $data["targetRoles"]));

        $data["targetRoles"] = $roles;
        $data["internalPublishDate"] = (new \DateTime($data["internalPublishDate"]))->format("Y-m-d");
        $data["externalPublishDate"] = (new \DateTime($data["externalPublishDate"]))->format("Y-m-d");

        $token = $this->sessionContext->getCurrentAccessTokenOrNull();
        $this->specialNoticeResponse = $this->specialNotice->createSpecialNotice($token, $data);

        $this->theSpecialNoticeIsCreated();
    }

    /**
     * @Given site with dvsa and vts users roles exists
     */
    public function siteWithDvsaAndVtsUsersRolesExists()
    {
        $this->vtsContext->createSite();
        $siteId = $this->vtsContext->getSite()["id"];

        $this->tester = $this->personContext->createTester(["siteIds" => [$siteId]]);
        $this->areaOffice1user = $this->testSupportHelper->getAreaOffice1Service()->create([]);
    }

    /**
     * @Given I publish Special Notice
     */
    public function iPublishSpecialNotice()
    {
        $token = $this->sessionContext->getCurrentAccessTokenOrNull();
        $id = $this->specialNoticeResponse->getBody()->toArray()["data"]["id"];

        $response = $this->specialNotice->publish($token, $id);

        PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @When the Special Notice is broadcasted
     */
    public function theSpecialNoticeIsBroadcasted()
    {
        $session = $this->session->startSession(Authentication::LOGIN_CRON_JOB_USER, Authentication::PASSWORD_DEFAULT);
        $response = $this->specialNotice->sendBroadcast($session->getAccessToken());

        PHPUnit_Framework_Assert::assertTrue($response);
    }

    /**
     * @Then users received Special Notice
     */
    public function usersReceivedSpecialNotice()
    {
        $this->assertInternalSpecialNotice();
        $this->assertExternalSpecialNotice();
    }

    private function assertInternalSpecialNotice()
    {
        $session = $this->session->startSession($this->areaOffice1user->data["username"], $this->areaOffice1user->data["password"]);
        $response = $this->specialNotice->getSpecialNotices($session->getAccessToken(), $session->getUserId());

        PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());

        $sn = $this->specialNoticeResponse->getBody()->toArray()["data"];
        $snContentid = $sn["id"];

        $today = new \DateTime("");
        $today->setTime(0,0,0);

        $internalPublishDate = new \DateTime($sn["internalPublishDate"]);
        $internalPublishDate->setTime(0,0,0);

        $userSpecialNotices = $response->getBody()->toArray()["data"];
        $found = false;
        foreach ($userSpecialNotices as $specialNotice) {
            if ($snContentid === $specialNotice["contentId"]) {
                $found = true;
                break;
            }
        }

        if ($internalPublishDate <= $today) {
            PHPUnit_Framework_Assert::assertTrue($found);
        } else {
            PHPUnit_Framework_Assert::assertFalse($found);
        }
    }

    private function assertExternalSpecialNotice()
    {
        $session = $this->session->startSession($this->tester->data["username"], $this->tester->data["password"]);
        $response = $this->specialNotice->getSpecialNotices($session->getAccessToken(), $session->getUserId());

        PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());

        $sn = $this->specialNoticeResponse->getBody()->toArray()["data"];
        $snContentid = $sn["id"];

        $today = new \DateTime("");
        $today->setTime(0,0,0);

        $externalPublishDate = new \DateTime($sn["externalPublishDate"]);
        $externalPublishDate->setTime(0,0,0);

        $userSpecialNotices = $response->getBody()->toArray()["data"];
        $found = false;
        foreach ($userSpecialNotices as $specialNotice) {
            if ($snContentid === $specialNotice["contentId"]) {
                $found = true;
                break;
            }
        }

        if ($externalPublishDate <= $today) {
            PHPUnit_Framework_Assert::assertTrue($found);
        } else {
            PHPUnit_Framework_Assert::assertFalse($found);
        }
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
