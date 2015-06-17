<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\EventUrlBuilder;

/**
 * Class Event_EventDetail
 */
class Event_EventDetail
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $eventId;

    private $apiResult;

    public function execute()
    {
        $this->apiResult = TestShared::execCurlForJsonFromUrlBuilder(
            $this,
            EventUrlBuilder::of()->event($this->eventId)
        );
    }

    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    public function eventType()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['type'];
        }
    }

    public function eventDescription()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['description'];
        }
    }

    public function eventDate()
    {
        if (isset($this->apiResult['data'])) {
            return $this->apiResult['data']['date'];
        }
    }
}
