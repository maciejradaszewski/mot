<?php

namespace DvsaEventApi\Service;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Event\EventDto;
use DvsaCommon\Dto\Event\EventFormDto;
use DvsaCommon\Dto\Event\EventListDto;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\Event;
use DvsaEntities\Entity\EventType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\EventRepository;
use DvsaEventApi\Service\Mapper\EventListMapper;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;


/**
 * This class is the service use by the event module.
 * It's going to be used to create a new event and list the event.
 *
 * Class EventService
 * @package DvsaEventApi\Service
 */
class EventService extends AbstractService
{
    /**
     * @var AuthorisationServiceInterface
     */
    private $authService;
    /**
     * @var DoctrineObject
     */
    private $objectHydrator;
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var EntityRepository
     */
    private $eventTypeRepository;
    /**
     * @var EventListMapper
     */
    private $mapper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Creates the event service class
     *
     * @param AuthorisationServiceInterface     $authService
     * @param EntityManager                     $entityManager
     * @param EventRepository                   $eventRepository
     * @param EntityRepository                  $eventTypeRepository
     * @param DoctrineObject                    $objectHydrator
     * @param EventListMapper                   $mapper
     */
    public function __construct(
        AuthorisationServiceInterface $authService,
        EntityManager $entityManager,
        EventRepository $eventRepository,
        EntityRepository $eventTypeRepository,
        DoctrineObject $objectHydrator,
        EventListMapper $mapper
    ) {
        parent::__construct($entityManager);

        $this->authService = $authService;
        $this->eventRepository = $eventRepository;
        $this->eventTypeRepository = $eventTypeRepository;
        $this->objectHydrator = $objectHydrator;
        $this->mapper = $mapper;
    }

    /**
     * @param $logger Object Something that can log an event somewhere
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Writes a single event into the event history log.
     *
     * @param $eventType   String a value from the \DvsaCommon\Enum\EventTypeCode event types list.
     * @param $description String a mandatory short description of the event
     * @param $eventDate   \DateTime the recorded event date
     *
     * @return Event  the new event history record
     *
     * @throws \Exception
     */
    public function addEvent($eventType, $description, \DateTime $eventDate)
    {
        try {
            $eventTypeObj = $this->validateEventType($eventType);

            /** @var  Event $event */
            $event = new Event();
            $event
                ->setEventType($eventTypeObj)
                ->setShortDescription($description)
                ->setEventDate($eventDate);

            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return $event;
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->log(
                    Logger::ERR,
                    'EventService::addEvent failed: ' . $e->getMessage()
                );
            }
            throw $e;
        }
    }

    /**
     * Ensures that the event type string is actually correctly typed and
     * corresponds to a real value. If the data fails to validate we throw
     * an exception otherwise we return the entity.
     *
     * @param $eventType String
     *
     * @return EventType $eventTypeEntity
     *
     * @throws \InvalidArgumentException
     */
    protected function validateEventType($eventType)
    {
        if (is_string($eventType) && EventTypeCode::exists($eventType)) {

            /** @var EventType $eventTypeObj */
            $eventTypeObj = $this->eventTypeRepository->findOneBy(['code' => $eventType]);

            if (is_null($eventTypeObj)) {
                throw new \InvalidArgumentException("Failed to get event type from database");
            }

            return $eventTypeObj;
        }
        throw new \InvalidArgumentException('Unrecognised event type');
    }

    /**
     * @param int           $id
     * @return EventDto
     * @throws NotFoundException
     */
    public function get($id)
    {
        $this->authService->assertGranted(PermissionInSystem::EVENT_READ);
        /* @var Event $event */
        $event = $this->eventRepository->findEvent($id);

        return $this->mapper->toDto($event);
    }

    /**
     * @param int           $id
     * @param string        $type
     * @param EventFormDto  $dto
     * @return EventListDto
     * @throws \UnexpectedValueException
     */
    public function getList($id, $type, $dto)
    {
        $this->authService->assertGranted(PermissionInSystem::LIST_EVENT_HISTORY);
        if (method_exists($this, 'getListFor' . ucfirst($type))) {
            return $this->{'getListFor' . ucfirst($type)}($id, $dto, $type);
        }
        throw new \UnexpectedValueException(
            'Invalid type passed, the type of event list must be ae/site/person'
        );
    }

    /**
     * @param int           $organisationId
     * @param EventFormDto  $dto
     * @param string        $type
     * @return EventListDto
     */
    protected function getListForAe($organisationId, EventFormDto $dto, $type)
    {
        $eventList = $this->eventRepository->findEvents($organisationId, $dto, $type);
        $eventCount = $this->eventRepository->findEventsCount($organisationId, $dto, $type);
        $dto = new EventListDto();
        $dto
            ->setOrganisationId($organisationId)
            ->setTotalResult($eventCount)
            ->setEvents($this->mapper->manyToDto($eventList));
        return $dto;
    }

    /**
     * @param int           $siteId
     * @param EventFormDto  $dto
     * @param string        $type
     * @return EventListDto
     */
    protected function getListForSite($siteId, EventFormDto $dto, $type)
    {
        $eventList = $this->eventRepository->findEvents($siteId, $dto, $type);
        $eventCount = $this->eventRepository->findEventsCount($siteId, $dto, $type);
        $dto = new EventListDto();
        $dto
            ->setSiteId($siteId)
            ->setTotalResult($eventCount)
            ->setEvents($this->mapper->manyToDto($eventList));
        return $dto;
    }

    /**
     * @param int           $personId
     * @param EventFormDto  $dto
     * @param string        $type
     * @return EventListDto
     */
    protected function getListForPerson($personId, EventFormDto $dto, $type)
    {
        $eventList = $this->eventRepository->findEvents($personId, $dto, $type);
        $eventCount = $this->eventRepository->findEventsCount($personId, $dto, $type);
        $dto = new EventListDto();
        $dto
            ->setPersonId($personId)
            ->setTotalResult($eventCount)
            ->setEvents($this->mapper->manyToDto($eventList));
        return $dto;
    }

    /**
     * This function test if the user have already an event register with a specific code
     *
     * @param Person $person
     * @param string $code
     * @return bool
     */
    public function isEventCreatedBy(Person $person, $code)
    {
        return $this->eventRepository->isEventCreatedBy($person, $code);
    }
}
