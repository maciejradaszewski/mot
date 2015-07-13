<?php

namespace DvsaMotApi\Helper;

use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\EventPersonMapRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Constants\EventDescription;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaEventApi\Service\EventService;
use DvsaEntities\Entity\EventPersonMap;
use DvsaEntities\Entity\Event;
use Doctrine\ORM\EntityManager;

class TesterQualificationStatusChangeEventHelper
{
    private $identityProviderInterface;

    private $dateTimeHolder;

    private $eventService;

    private $personRepository;

    private $eventPersonMapRepository;


    public function __construct(
        MotIdentityProviderInterface $identityProviderInterface,
        EventService $eventService,
        EventPersonMapRepository $eventPersonMapRepository,
        PersonRepository $personRepository,
        DateTimeHolder $dateTimeHolder
    ) {
        $this->identityProviderInterface = $identityProviderInterface;
        $this->eventService = $eventService;
        $this->eventPersonMapRepository = $eventPersonMapRepository;
        $this->dateTimeHolder = $dateTimeHolder;
        $this->personRepository = $personRepository;
    }

    /**
     * @param Person $person
     * @param string $group
     * @return EventPersonMap
     */
    public function create(Person $person, $group)
    {
        if ($group === VehicleClassGroupCode::BIKES) {
            return $this->createEventForGroupA($person);
        } elseif ($group === VehicleClassGroupCode::CARS_ETC) {
            return $this->createEventForGroupB($person);
        }

        throw new \InvalidArgumentException("Group \"" . $group. "\"  not found.");
    }

    /**
     * @param Person $person
     * @return EventPersonMap
     * @throws \Exception
     */
    private function createEventForGroupA(Person $person)
    {
        $event = $this->eventService->addEvent(
            EventTypeCode::GROUP_A_TESTER_QUALIFICATION,
            sprintf(EventDescription::TESTER_QUALIFICATION_STATUS_CHANGE, VehicleClassGroupCode::BIKES, $this->getUsername()),
            $this->dateTimeHolder->getCurrent(true)
        );

        return $this->createEventPersonMap($person, $event);
    }

    /**
     * @param Person $person
     * @return EventPersonMap
     * @throws \Exception
     */
    private function createEventForGroupB(Person $person)
    {
        $event = $this->eventService->addEvent(
            EventTypeCode::GROUP_B_TESTER_QUALIFICATION,
            sprintf(EventDescription::TESTER_QUALIFICATION_STATUS_CHANGE, VehicleClassGroupCode::CARS_ETC, $this->getUsername()),
            $this->dateTimeHolder->getCurrent(true)
        );

        return $this->createEventPersonMap($person, $event);
    }

    /**
     * @param Person $person
     * @param Event $event
     * @return EventPersonMap
     */
    private function createEventPersonMap(Person $person, Event $event)
    {
        $eventMap = (new EventPersonMap())
            ->setEvent($event)
            ->setPerson($person);

        $this->eventPersonMapRepository->save($eventMap);

        return $eventMap;
    }

    /**
     * @return string
     */
    private function getUsername()
    {
        $user = $this->personRepository->get($this->identityProviderInterface->getIdentity()->getUserId());
        return $user->getDisplayName();
    }
}
