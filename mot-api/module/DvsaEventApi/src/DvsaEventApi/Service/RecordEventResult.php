<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaEventApi\Service;

use DvsaEntities\Entity\Event;

/**
 * Holds the result of a record event operation.
 */
class RecordEventResult
{
    /**
     * @param Event|null $event
     */
    public function __construct(Event $event = null)
    {
        $this->event = $event;
    }

    /**
     * @var Event|null
     */
    private $event = null;

    /**
     * @return int
     */
    public function getEventId()
    {
        return $this->event ? $this->event->getId() : null;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return (bool) $this->event;
    }
}
