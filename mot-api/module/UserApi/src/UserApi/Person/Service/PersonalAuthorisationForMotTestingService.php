<?php

namespace UserApi\Person\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonApi\Service\EntityFinderTrait;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;
use UserApi\Person\Dto\MotTestingAuthorisationCollector;
use UserApi\Person\Service\Validator\PersonalAuthorisationForMotTestingValidator;

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

    public function __construct(
        EntityManager $entityManager,
        NotificationService $notificationService,
        PersonalAuthorisationForMotTestingValidator $validator
    ) {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
        $this->validator = $validator;
    }

    /**
     * @param int   $personId
     * @param array $data
     *
     * @return MotTestingAuthorisationCollector
     */
    public function updatePersonalTestingAuthorisationGroup($personId, $data)
    {
        $this->validator->validate($data);

        $classes = [];

        if (self::SUCCESS === (int)$data['result']) {
            if (self::GROUP_A_VEHICLE === (int)$data['group']) {
                $classes = [
                    'class' . VehicleClassCode::CLASS_1 => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                    'class' . VehicleClassCode::CLASS_2 => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                ];
            } else {
                $classes = [
                    'class' . VehicleClassCode::CLASS_3  => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                    'class' . VehicleClassCode::CLASS_4  => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                    'class' . VehicleClassCode::CLASS_5  => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                    'class' . VehicleClassCode::CLASS_7  => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                ];
            }

            $this->sendNotificationInitialTrainingPassed($personId);

        } else {
            $this->sendNotificationInitialTrainingFailed($personId);
        }

        return $this->updatePersonalTestingAuthorisation($personId, $classes);
    }

    /**
     * @param int   $personId
     * @param array $data
     *
     * @return MotTestingAuthorisationCollector
     */
    private function updatePersonalTestingAuthorisation($personId, $data)
    {
        $applicationClasses = $this->getPersonalTestingAuthorisationList($personId);

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

    private function sendNotificationInitialTrainingPassed($personId)
    {
        $this->sendNotification($personId, Notification::TEMPLATE_TESTER_INITIAL_TRAINING_PASSED);
    }

    private function sendNotificationInitialTrainingFailed($personId)
    {
        $this->sendNotification($personId, Notification::TEMPLATE_TESTER_INITIAL_TRAINING_FAILED);
    }

    private function sendNotification($personId, $templateId, $fields = [])
    {
        $notification = (new Notification())
            ->setRecipient($personId)
            ->setTemplate($templateId)
            ->setFields($fields);

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
