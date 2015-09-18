<?php

namespace DvsaCommon\Dto\Event;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaEntities\Entity\EventOutcome;
use DvsaEntities\Entity\Person;

/**
 * Class EventDto
 * @package DvsaCommon\Dto\Event
 */
class EventDto extends AbstractDataTransferObject
{
    /* @var int     $type */
    private $id;

    /* @var string  $type */
    private $type;

    /* @var string  $date */
    private $date;

    /* @var string  $description */
    private $description;

    /**
     * @var string $addedByName
     * */
    private $addedByName;

    /**
     * @var string $eventOutcomeDescription
     */
    private $eventOutcomeDescription;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|null $addedByName
     *
     * @return $this
     */
    public function setAddedByName($addedByName = null)
    {
        $this->addedByName = $addedByName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddedByName()
    {
        return $this->addedByName;
    }

    /**
     * @param string|null $eventOutcomeDescription
     *
     * @return $this
     */
    public function setEventOutcomeDescription($eventOutcomeDescription = null)
    {
        $this->eventOutcomeDescription = $eventOutcomeDescription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEventOutcomeDescription()
    {
        return $this->eventOutcomeDescription;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
