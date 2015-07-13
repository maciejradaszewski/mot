<?php

namespace DvsaMotApi\ApiTest\Person\Helper;

use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\EventType;
use DvsaEntities\Entity\Person;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Constants\EventDescription;
use DvsaEntities\Repository\PersonRepository;
use DvsaEventApi\Service\EventService;
use DvsaMotApi\Helper\TesterQualificationStatusChangeEventHelper;
use DvsaEntities\Repository\EventPersonMapRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use Doctrine\ORM\EntityManager;
use DvsaEntities\Repository\EventRepository;
use Doctrine\ORM\EntityRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaEventApi\Service\Mapper\EventListMapper;
use DvsaCommon\Date\DateTimeHolder;

class TesterQualificationStatusChangeEventHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var MotIdentityProviderInterface */
    private $identityProvider;

    /** @var PersonRepository */
    private $personRepository;

    /** @var EventPersonMapRepository */
    private $eventPersonMapRepository;

    /** @var Person */
    private $person;

    /** @var Person */
    private $tester;

    public function setUp()
    {
        $personId = 1;
        $identity = XMock::of(MotIdentityInterface::class);
        $identity
            ->expects($this->any())
            ->method('getUserId')
            ->willReturn($personId);

        $identityProvider = XMock::of(MotIdentityProviderInterface::class);
        $identityProvider
            ->expects($this->any())
            ->method('getIdentity')
            ->willReturn($identity);

        $person = (new Person())
            ->setId($personId)
            ->setFirstName("John")
            ->setFamilyName("Rambo");

        $personRepository = XMock::of(PersonRepository::class);
        $personRepository
            ->expects($this->any())
            ->method('get')
            ->willReturn($person);

        $tester = (new Person())
            ->setId(123)
            ->setFirstName("Marty")
            ->setFamilyName("McFly");

        $this->identityProvider = $identityProvider;
        $this->personRepository = $personRepository;
        $this->person = $person;
        $this->tester = $tester;
        $this->eventPersonMapRepository = XMock::of(EventPersonMapRepository::class);
    }

    public function testEventIsSendForGroupA()
    {
        $eventType = (new EventType())->setCode(EventTypeCode::GROUP_A_TESTER_QUALIFICATION);
        $testerQualificationStatusChangeEvent = $this->createTesterQualificationStatusChangeEventHelper($eventType);

        $eventPersonMap = $testerQualificationStatusChangeEvent->create($this->tester, "A");
        $event = $eventPersonMap->getEvent();
        $description = sprintf(EventDescription::TESTER_QUALIFICATION_STATUS_CHANGE, "A", $this->person->getDisplayName());

        $this->assertEquals($this->tester, $eventPersonMap->getPerson());
        $this->assertEquals($description, $event->getShortDescription());
    }

    public function testEventIsSendForGroupB()
    {
        $eventType = (new EventType())->setCode(EventTypeCode::GROUP_B_TESTER_QUALIFICATION);
        $testerQualificationStatusChangeEvent = $this->createTesterQualificationStatusChangeEventHelper($eventType);;

        $eventPersonMap = $testerQualificationStatusChangeEvent->create($this->tester, "B");
        $event = $eventPersonMap->getEvent();
        $description = sprintf(EventDescription::TESTER_QUALIFICATION_STATUS_CHANGE, "B", $this->person->getDisplayName());

        $this->assertEquals($this->tester, $eventPersonMap->getPerson());
        $this->assertEquals($description, $event->getShortDescription());
    }

    private function createEventService(EventType $eventType)
    {
        $entityRepository = XMock::of(EntityRepository::class, ['findOneBy']);
        $entityRepository
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturn($eventType);

        return new EventService(
            XMock::of(AuthorisationServiceInterface::class),
            XMock::of(EntityManager::class),
            XMock::of(EventRepository::class),
            $entityRepository,
            XMock::of(DoctrineObject::class),
            XMock::of(EventListMapper::class)
        );
    }

    /**
     * @param EventType $eventType
     * @return TesterQualificationStatusChangeEventHelper
     * @throws \Exception
     */
    private function createTesterQualificationStatusChangeEventHelper(EventType $eventType)
    {
        return new TesterQualificationStatusChangeEventHelper(
            $this->identityProvider,
            $this->createEventService($eventType),
            $this->eventPersonMapRepository,
            $this->personRepository,
            new DateTimeHolder()
        );
    }
}
