<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Api\Event;
use Dvsa\Mot\Behat\Support\Api\Person;
use Dvsa\Mot\Behat\Support\Api\Session;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;

class EventContext implements Context
{
    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var PersonContext
     */
    private $personContext;

    /**
     * @var VtsContext
     */
    private $vtsContext;

    /**
     * @var AuthorisedExaminerContext
     */
    private $aeContext;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TestSupportHelper
     */
    private $testSupportHelper;

    /** @var array */
    private $siteEvent = [];

    /**
     * @param Event $event
     */
    public function __construct(Event $event, Person $person, Session $session, TestSupportHelper $testSupportHelper)
    {
        $this->event = $event;
        $this->person = $person;
        $this->session = $session;
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
     * @Then a status change event is generated for the user of :eventType
     */
    public function aStatusChangeEventIsGeneratedForTheUserOf($eventType)
    {
        $response = $this->event->getPersonEventsData($this->sessionContext->getCurrentAccessToken(), $this->personContext->getPersonUserId());
        $data = $response->getBody()->toArray()["data"];
        $eventList = $data["events"];

        PHPUnit::assertNotEmpty($eventList);

        $found = false;
        foreach ($eventList as $event) {
            if ($event["type"] === $eventType) {
                $this->userEvent = $event;
                $found = true;
                break;
            }
        }

        PHPUnit::assertTrue($found, "Event type {$eventType} not found");
    }

    /**
     * @Then an event description contains phrase :phrase
     */
    public function anEventDescriptionContainsPhrase($phrase)
    {
        PHPUnit::assertContains($phrase, $this->userEvent["description"]);
    }

    /**
     * @Then an event description contains my name
     */
    public function anEventDescriptionContainsMyName()
    {
        $this->anEventDescriptionContainsPhrase($this->getPersonDisplayName());
    }

    /**
     * @Then a status change event is NOT generated for the user of :eventType
     */
    public function aStatusChangeEventIsNotGeneratedForTheUserOf($eventType)
    {
        $response = $this->event->getPersonEventsData($this->sessionContext->getCurrentAccessToken(), $this->personContext->getPersonUserId());
        $data = $response->getBody()->toArray()["data"];
        $eventList = $data["events"];

        $found = false;
        foreach ($eventList as $event) {
            if ($event["type"] === $eventType) {
                $found = true;
                break;
            }
        }

        PHPUnit::assertFalse($found);
    }

    /**
     * @Then a site event is generated for the site of :eventType
     */
    public function aSiteEventIsGeneratedForTheSiteOf($eventType)
    {
        $areaOffice1Service = $this->testSupportHelper->getAreaOffice1Service();
        $ao = $areaOffice1Service->create([]);
        $aoSession = $this->session->startSession(
            $ao->data["username"],
            $ao->data["password"]
        );

        $site = $this->vtsContext->getSite();

        $response = $this->event->getSiteEventsData($aoSession->getAccessToken(), $site["id"]);
        $data = $response->getBody()->toArray()["data"];
        $eventList = $data["events"];

        $found = false;
        foreach ($eventList as $event) {
            if ($event["type"] === $eventType) {
                $this->siteEvent = $event;
                $found = true;
                break;
            }
        }

        PHPUnit::assertTrue($found);
    }

    /**
     * @Then an organisation event is generated for the organisation of :eventType
     */
    public function anOrganisationEventIsGeneratedForTheOrganisationOf($eventType)
    {
        $areaOffice1Service = $this->testSupportHelper->getAreaOffice1Service();
        $ao = $areaOffice1Service->create([]);
        $aoSession = $this->session->startSession(
            $ao->data["username"],
            $ao->data["password"]
        );

        $ae = $this->aeContext->getAE();

        $response = $this->event->getOrganisationEventsData($aoSession->getAccessToken(), $ae["id"]);
        $data = $response->getBody()->toArray()["data"];
        $eventList = $data["events"];

        $found = false;
        foreach ($eventList as $event) {
            if ($event["type"] === $eventType) {
                $this->siteEvent = $event;
                $found = true;
                break;
            }
        }

        PHPUnit::assertTrue($found);
    }

    private function getPersonDisplayName()
    {
        $response = $this->person->getPersonDetails($this->sessionContext->getCurrentAccessToken(),$this->sessionContext->getCurrentUserId());
        $data = $response->getBody()->toArray()["data"];

        return implode(" ", array_filter([$data["firstName"], $data["middleName"], $data["surname"]]));
    }
}
