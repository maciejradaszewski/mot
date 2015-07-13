<?php

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PHPUnit_Framework_Assert as PHPUnit;
use Dvsa\Mot\Behat\Support\Api\Event;
use Dvsa\Mot\Behat\Support\Api\Person;
use Behat\Gherkin\Node\TableNode;

class EventContext implements \Behat\Behat\Context\SnippetAcceptingContext
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
     * @var Event
     */
    private $event;

    /**
     * @var Person
     */
    private $person;

    /**
     * @param Event $event
     */
    public function __construct(Event $event, Person $person)
    {
        $this->event = $event;
        $this->person = $person;
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
     * @Then a status change event is generated for the user of :eventType
     */
    public function aStatusChangeEventIsGeneratedForTheUserOf($eventType)
    {
        $response = $this->event->getPersonEventData($this->sessionContext->getCurrentAccessToken(), $this->personContext->getPersonUserId());
        $data = $response->getBody()->toArray()["data"];
        $eventList = $data["events"];

        PHPUnit::assertNotEmpty($eventList);

        $found = false;
        foreach ($eventList as $event) {
            if ($event["type"] === $eventType) {
                PHPUnit::assertContains($this->getPersonDisplayName(), $event["description"]);
                $found = true;
                break;
            }
        }

        PHPUnit::assertTrue($found, "Event type {$eventType} not found");
    }

    /**
     * @Then a status change event is NOT generated for the user of :eventType
     */
    public function aStatusChangeEventIsNotGeneratedForTheUserOf($eventType)
    {
        $response = $this->event->getPersonEventData($this->sessionContext->getCurrentAccessToken(), $this->personContext->getPersonUserId());
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

    private function getPersonDisplayName()
    {
        $response = $this->person->getPersonDetails($this->sessionContext->getCurrentAccessToken(),$this->sessionContext->getCurrentUserId());
        $data = $response->getBody()->toArray()["data"];

        $name = $data["firstName"];
        if ($data["middleName"]) {
            $name .= " " .$data["middleName"];
        }

        $name .= " " .$data["surname"];

        return $name;
    }
}