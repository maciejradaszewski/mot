<?php

namespace DvsaEventApiTest\Service\Mapper;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Event\EventDto;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaEntities\Entity\Event;
use DvsaEntities\Entity\EventType;
use DvsaEventApi\Service\Mapper\EventListMapper;
use Zend\Stdlib\DateTime;

/**
 * Class EventListMapperTest
 *
 * @package DvsaEventApiTest\Service\Mapper
 */
class EventListMapperTest extends AbstractServiceTestCase
{
    public function testEventListMapperToDto()
    {
        $date = new \DateTime('now');

        $eventType = new EventType();
        $eventType->setDescription('Type description');

        $event = new Event();
        $event->setId(1);
        $event->setEventDate($date);
        $event->setShortDescription('Short description');
        $event->setEventType($eventType);

        $eventListMapper = new EventListMapper();

        $dto = $eventListMapper->toDto($event);
        $this->assertInstanceOf(EventDto::class, $dto);
        $this->assertSame($event->getId(), $dto->getId());
        $this->assertSame(DateTimeApiFormat::dateTime($date), $dto->getDate());
        $this->assertSame($event->getShortDescription(), $dto->getDescription());
        $this->assertSame($event->getEventType()->getDescription(), $dto->getType());
    }
}
