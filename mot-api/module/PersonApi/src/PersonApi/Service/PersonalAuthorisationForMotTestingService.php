<?php

namespace PersonApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\EventDescription;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonApi\Service\EntityFinderTrait;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\EventPersonMap;
use DvsaEventApi\Service\EventService;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;
use PersonApi\Dto\MotTestingAuthorisationCollector;
use PersonApi\Service\Validator\PersonalAuthorisationForMotTestingValidator;
use DvsaAuthorisation\Service\AuthorisationService;

/**
 * Personal Authorisation For Mot Testing
 */
class PersonalAuthorisationForMotTestingService
{
    use EntityFinderTrait;

    const GROUP_A_VEHICLE = 1;
    const GROUP_B_VEHICLE = 2;
    const SUCCESS = 1;

    /** @var $notificationService NotificationService */
    private $notificationService;

    /** @var $validator PersonalAuthorisationForMotTestingValidator */
    private $validator;
    /** @var  AuthorisationService */
    private $authorisationService;

    /** @var EventService  */
    private $eventService;

    /** @var PersonService  */
    private $personService;
    private $vehicleClassGroupLookup = ['1' => 'A', '2' => 'B'];
    private $authStatusLookup = [
        'UNKN' => 'Unknown',
        'SPND' => 'Suspended',
        'QLFD' => 'Qualified',
        'DMTN' => 'Demo test needed',
        'ITRN' => 'Initial training needed',
    ];

    public function __construct(
        EntityManager $entityManager,
        NotificationService $notificationService,
        PersonalAuthorisationForMotTestingValidator $validator,
        AuthorisationService $authorisationService,
        EventService $eventService,
        PersonService $personService
    ) {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
        $this->validator = $validator;
        $this->authorisationService = $authorisationService;
        $this->eventService = $eventService;
        $this->personService = $personService;
    }

    /**
     * @param int   $personId
     * @param array $data
     *
     * @return MotTestingAuthorisationCollector
     */
    public function updatePersonalTestingAuthorisationGroup($personId, $data)
    {
        $this->authorisationService->assertGranted(PermissionInSystem::ALTER_TESTER_AUTHORISATION_STATUS);

        $this->validator->validate($data);

        $tester = $this->personService->getPersonById($personId);

        $classes = [];
        $group = (int)$data['group'];
        $status = $data['result'];

        if (self::GROUP_A_VEHICLE === $group) {
            $classes = [
                'class' . VehicleClassCode::CLASS_1 => $status,
                'class' . VehicleClassCode::CLASS_2 => $status,
            ];
        } elseif (self::GROUP_B_VEHICLE === $group) {
            $classes = [
                'class' . VehicleClassCode::CLASS_3 => $status,
                'class' . VehicleClassCode::CLASS_4 => $status,
                'class' . VehicleClassCode::CLASS_5 => $status,
                'class' . VehicleClassCode::CLASS_7 => $status,
            ];
        }

        $applicationClasses = $this->getPersonalTestingAuthorisationList($personId);

        $this->sendNotification(
            $personId,
            $this->vehicleClassGroupLookup[$group],
            $this->authStatusLookup[$this->getStatusCodeFromAuthorisationListForGroup($applicationClasses, $group)],
            $this->authStatusLookup[$status]
        );

        if ($group === 1) {
            $eventTypeCode = EventTypeCode::GROUP_A_TESTER_QUALIFICATION;
            $groupName = 'A';
        } else {
            $eventTypeCode = EventTypeCode::GROUP_B_TESTER_QUALIFICATION;
            $groupName = 'B';
        }
        $event = $this->eventService->addEvent(
            $eventTypeCode,
            sprintf(
                EventDescription::TESTER_QUALIFICATION_STATUS_CHANGE_NEW,
                $groupName,
                $this->authStatusLookup[$this->getStatusCodeFromAuthorisationListForGroup($applicationClasses, $group)],
                $this->authStatusLookup[$status],
                $this->authorisationService->getIdentity()->getUsername()
            ),
            new \DateTime()
        );

        $eventPersonMap = new EventPersonMap();
        $eventPersonMap->setEvent($event)
            ->setPerson($tester);

        $this->entityManager->persist($eventPersonMap);

        return $this->updatePersonalTestingAuthorisation($applicationClasses, $classes);
    }

    /**
     * @param array $authorisationList
     * @param       $group
     *
     * @throws \Exception
     * @return string
     */
    private function getStatusCodeFromAuthorisationListForGroup($authorisationList, $group)
    {
        /** @var $vcAuth AuthorisationForTestingMot */
        foreach ($authorisationList as $vcAuth) {
            if ($group == self::GROUP_A_VEHICLE && in_array($vcAuth->getVehicleClass()->getCode(), [1, 2])) {
                return $vcAuth->getStatus()->getCode();
            } elseif ($group == self::GROUP_B_VEHICLE
                && in_array(
                    $vcAuth->getVehicleClass()->getCode(), [3, 4, 5, 6]
                )
            ) {
                return $vcAuth->getStatus()->getCode();
            }
        }
        throw new \Exception("Authorisation list does not contain record for vehicle group " . $group);
    }

    /**
     * @param int   $applicationClasses
     * @param array $data
     *
     * @return MotTestingAuthorisationCollector
     */
    private function updatePersonalTestingAuthorisation($applicationClasses, $data)
    {
        if ($applicationClasses) {
            /** @var $vcAuth AuthorisationForTestingMot */
            foreach ($applicationClasses as $vcAuth) {
                $key = 'class' . $vcAuth->getVehicleClass()->getCode();
                if (isset($data[$key])) {
                    $vcAuth->setStatus($this->getStatusByCode($data[$key]));
                    $this->entityManager->persist($vcAuth);
                }
            }
            $this->entityManager->flush();
        }

        return new MotTestingAuthorisationCollector($applicationClasses);
    }

    /**
     * @param int $personId
     *
     * @return MotTestingAuthorisationCollector
     */
    public function getPersonalTestingAuthorisation($personId)
    {
        $authList = $this->getPersonalTestingAuthorisationList($personId);

        return new MotTestingAuthorisationCollector($authList);
    }

    /**
     * @param string $code
     *
     * @return AuthorisationForTestingMotStatus
     *
     * @throws NotFoundException
     */
    private function getStatusByCode($code)
    {
        $status = $this->findOneByOrThrowException(
            AuthorisationForTestingMotStatus::class,
            ['code' => $code]
        );

        return $status;
    }

    private function sendNotification($personId, $group, $fromStatus, $toStatus)
    {
        $notification = (new Notification())
            ->setRecipient($personId)
            ->setTemplate(Notification::TEMPLATE_TESTER_STATUS_CHANGE)
            ->setFields(
                [
                    'group'          => $group,
                    'previousStatus' => $fromStatus,
                    'newStatus'      => $toStatus,
                ]
            );

        $this->notificationService->add($notification->toArray());
    }

    /**
     * @param $personId
     *
     * @return \DvsaEntities\Entity\AuthorisationForTestingMot[]
     */
    private function getPersonalTestingAuthorisationList($personId)
    {
        $person = $this->findPerson($personId);

        return $person->getAuthorisationsForTestingMot();
    }
}
