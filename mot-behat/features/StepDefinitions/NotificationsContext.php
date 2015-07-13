<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Api\Notification;
use PHPUnit_Framework_Assert as PHPUnit;

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
     * @var PersonContext
     */
    private $personContext;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    public function __construct(
        Notification $notification,
        Session $session
    )
    {
        $this->notification = $notification;
        $this->session = $session;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->personContext = $scope->getEnvironment()->getContext(PersonContext::class);
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
    }

    /**
     * @Then the user will receive a status change notification for group :group
     */
    public function theUserWillReceiveAStatusChangeNotificationForGroup($group)
    {
        $notifications = $this->getNotifications();
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
        $notifications = $this->getNotifications();

        $found = false;
        foreach ($notifications['data'] as $notification) {
            if ($notification['templateId'] == self::TEMPLATE_TESTER_QUALIFICATION_STATUS) {
                $found = true;
                break;
            }
        }

        PHPUnit::assertFalse($found);
    }

    private function getNotifications()
    {
        $session = $this->session->startSession(
            $this->personContext->getPersonUsername(),
            $this->personContext->getPersonPassword()
        );

        /** @var Dvsa\Mot\Behat\Support\Response */
        $notificationResponse = $this->notification->fetchNotificationForPerson(
            $session->getAccessToken(),
            $session->getUserId()
        );

        return $notificationResponse->getBody()->toArray();
    }
}
