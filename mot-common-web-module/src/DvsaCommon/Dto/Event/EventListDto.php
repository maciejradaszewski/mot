<?php

namespace DvsaCommon\Dto\Event;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class EventListDto
 * @package DvsaCommon\Dto\Event
 */
class EventListDto extends AbstractDataTransferObject
{
    /* @var int $organisationId */
    private $organisationId;

    /* @var int $siteId */
    private $siteId;

    /* @var int $personId */
    private $personId;

    /* @var int $totalResult */
    private $totalResult;

    /* @var EventDto[] $events */
    private $events;

    /**
     * @return int
     */
    public function getOrganisationId()
    {
        return $this->organisationId;
    }

    /**
     * @param int $organisationId
     * @return $this
     */
    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     * @return $this
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     * @return $this
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalResult()
    {
        return $this->totalResult;
    }

    /**
     * @param int $totalResult
     * @return $this
     */
    public function setTotalResult($totalResult)
    {
        $this->totalResult = $totalResult;
        return $this;
    }

    /**
     * @return EventDto[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param EventDto[] $events
     * @return $this
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }
}
