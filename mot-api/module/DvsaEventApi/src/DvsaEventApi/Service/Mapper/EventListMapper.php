<?php

namespace DvsaEventApi\Service\Mapper;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Event\EventDto;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\Event;

/**
 * Class EventListMapper
 *
 * @package DvsaEventApi\Service\Mapper
 */
class EventListMapper extends AbstractApiMapper
{
    /**
     * @param Event $event
     *
     * @return EventDto
     */
    public function toDto($event)
    {
        $dto = new EventDto();
        $dto
            ->setId($event->getId())
            ->setDate(DateTimeApiFormat::dateTime($event->getEventDate()))
            ->setDescription($event->getShortDescription())
            ->setType($event->getEventType()->getDescription());

        return $dto;
    }
}
