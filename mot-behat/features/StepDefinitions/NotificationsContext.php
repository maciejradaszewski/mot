<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Notification;
use Dvsa\Mot\Behat\Support\Api\Vts;
use Dvsa\Mot\Behat\Support\Api\AuthorisedExaminer;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;

class NotificationsContext implements Context
{
    const TEMPLATE_TESTER_QUALIFICATION_STATUS = 14;

    /**
     * @var Notification
     */
    private $notification;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Vts
     */
    private $vts;

    /**
     * @var AuthorisedExaminer
     */
    private $ae;

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /**
     * @var PersonContext
     */
    private $personContext;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var VtsContext
     */
    private $vtsContext;

    /**
     * @var AuthorisedExaminerContext
     */
    private $aeContext;

    public function __construct(
        Notification $notification,
        Session $session,
        Vts $vts,
        AuthorisedExaminer $ae,
        TestSupportHelper $testSupportHelper
    )
    {
        $this->notification = $notification;
        $this->session = $session;
        $this->vts = $vts;
        $this->ae = $ae;
        $this->testSupportHelper = $testSupportHelper;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->personContext = $scope->getEnvironment()->getContext(PersonContext::class);
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->vtsContext = $scope->getEnvironment()->getContext(VtsContext::class);
        $this->aeContext = $scope->getEnvironment()->getContext(AuthorisedExaminerContext::class);
    }

    /**
     * @Then the user will receive a status change notification for group :group
     */
    public function theUserWillReceiveAStatusChangeNotificationForGroup($group)
    {
        $session = $this->session->startSession(
            $this->personContext->getPersonUsername(),
            $this->personContext->getPersonPassword()
        );

        $response = $this->notification->fetchNotificationForPerson($session->getAccessToken(), $session->getUserId());
        $notifications = $response->getBody()->toArray();

        PHPUnit::assertNotEmpty($notifications);

        // Assert that the notification that we are expecting exists
        $found = false;
        foreach ($notifications['data'] as $notification) {
            if ($notification['templateId'] == self::TEMPLATE_TESTER_QUALIFICATION_STATUS) {
                PHPUnit::assertContains('Group '.$group, $notification['subject']);
                PHPUnit::assertContains('Group '.$group, $notification['content']);
                $found = true;
                break;
            }
        }
        PHPUnit::assertTrue($found, 'Notification with template '.self::TEMPLATE_TESTER_QUALIFICATION_STATUS.' not found');
    }

    /**
     * @Then the user will NOT receive a status change notification
     */
    public function theUserWillNotReceiveAStatusChangeNotification()
    {
        $session = $this->session->startSession(
            $this->personContext->getPersonUsername(),
            $this->personContext->getPersonPassword()
        );

        $response = $this->notification->fetchNotificationForPerson($session->getAccessToken(), $session->getUserId());
        $notifications = $response->getBody()->toArray();

        $found = false;
        foreach ($notifications['data'] as $notification) {
            if ($notification['templateId'] == self::TEMPLATE_TESTER_QUALIFICATION_STATUS) {
                $found = true;
                break;
            }
        }

        PHPUnit::assertFalse($found);
    }

    /**
     * @When a user accepts nomination to :role site role
     */
    public function aUserAcceptsNominationToSiteRole($role)
    {
        $userSession = $this->session->startSession(
            $this->personContext->getPersonUsername(),
            $this->personContext->getPersonPassword()
        );

        $userToken = $userSession->getAccessToken();
        $notification = $this->notification->getRoleNominationNotification($role, $this->personContext->getPersonUserId(), $userToken);

        $response = $this->notification->acceptSiteNomination($userToken, $notification["id"]);;
        PHPUnit::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @When a user accepts nomination to :role organisation role
     */
    public function aUserAcceptsNominationToOrganisationRole($role)
    {
        $userSession = $this->session->startSession(
            $this->personContext->getPersonUsername(),
            $this->personContext->getPersonPassword()
        );

        $userToken = $userSession->getAccessToken();
        $notification = $this->notification->getRoleNominationNotification($role, $this->personContext->getPersonUserId(), $userToken);

        $response = $this->notification->acceptOrganisationNomination($userToken, $notification["id"]);;
        PHPUnit::assertEquals(200, $response->getStatusCode());
    }
}
