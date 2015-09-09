<?php

namespace SiteApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Constants\EventDescription;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\AuthorisationForTestingMotAtSiteStatusCode;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Enum\SiteStatusCode;
use DvsaCommonApi\Filter\XssFilter;
use DvsaEntities\Entity\AuthorisationForTestingMotAtSite;
use DvsaEntities\Entity\AuthorisationForTestingMotAtSiteStatus;
use DvsaEntities\Entity\EventSiteMap;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteStatus;
use DvsaEntities\Entity\VehicleClass;
use DvsaEntities\Repository\AuthorisationForTestingMotAtSiteStatusRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEntities\Repository\SiteStatusRepository;
use DvsaEntities\Repository\VehicleClassRepository;
use DvsaEventApi\Service\EventService;
use SiteApi\Service\Validator\SiteValidator;

class SiteDetailsService
{
    const NAME_FIELD = 'Name';
    const STATUS_FIELD = 'Status';
    const CLASSES_FIELD = 'Testing classes';
    const AREA_OFFICE_FIELD = 'area_office';
    const OLD_VALUE = 'old';
    const NEW_VALUE = 'new';

    /**
     * @var SiteRepository
     */
    private $siteRepository;
    /**
     * @var AuthorisationServiceInterface
     */
    private $authService;
    /**
     * @var UpdateVtsAssertion
     */
    private $updateVtsAssertion;
    /**
     * @var XssFilter
     */
    private $xssFilter;
    /**
     * @var SiteValidator
     */
    private $siteValidator;
    /**
     * @var EventService
     */
    private $eventService;
    /**
     * @var DateTimeHolder
     */
    private $dateTimeHolder;
    /**
     * @var MotIdentityInterface
     */
    private $identity;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var VehicleClassRepository
     */
    private $vehicleClassRepository;
    /**
     * @var AuthorisationForTestingMotAtSiteStatusRepository
     */
    private $authForTestingMotStatusRepository;
    /**
     * @var SiteStatusRepository
     */
    private $siteStatusRepository;

    public function __construct(
        SiteRepository $siteRepository,
        MotAuthorisationServiceInterface $authService,
        UpdateVtsAssertion $updateVtsAssertion,
        XssFilter $xssFilter,
        SiteValidator $siteValidator,
        EventService $eventService,
        MotIdentityInterface $identity,
        EntityManager $entityManager,
        VehicleClassRepository $vehicleClassRepository,
        AuthorisationForTestingMotAtSiteStatusRepository $authForTestingMotStatusRepository,
        SiteStatusRepository $siteStatusRepository
    )
    {
        $this->siteRepository = $siteRepository;
        $this->authService = $authService;
        $this->updateVtsAssertion = $updateVtsAssertion;
        $this->xssFilter = $xssFilter;
        $this->siteValidator = $siteValidator;
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;

        $this->dateTimeHolder = new DateTimeHolder();
        $this->identity = $identity;
        $this->vehicleClassRepository = $vehicleClassRepository;
        $this->authForTestingMotStatusRepository = $authForTestingMotStatusRepository;
        $this->siteStatusRepository = $siteStatusRepository;
    }

    public function update($siteId, VehicleTestingStationDto $dto)
    {
        $this->updateVtsAssertion->assertGranted($siteId);

        /** @var VehicleTestingStationDto $dto */
        $dto = $this->xssFilter->filter($dto);
        $this->siteValidator->validateSiteDetailOnEdit($dto);

        if ($dto->isNeedConfirmation() === true) {
            return true;
        }

        /** @var Site $site */
        $site = $this->siteRepository->get($siteId);
        $this->updateSiteDetails($site, $dto);

        return [
            'success' => true
        ];
    }

    private function updateSiteDetails(Site $site, VehicleTestingStationDto $data)
    {
        $diff = [];

        $this->updateName($site, $data, $diff);
        $this->updateClasses($site, $data, $diff);
        $this->updateStatus($site, $data, $diff);

        $this->raiseSiteEvents($site, $diff);

        $this->entityManager->persist($site);
        $this->entityManager->flush();

        return $diff;
    }

    private function updateName(Site $site, VehicleTestingStationDto $dto, array & $diff)
    {
        $old = $site->getName();
        $new = $dto->getName();

        if($old !== $new){
            $site->setName($new);
            $this->createDiffArray($diff, $old, $new, self::NAME_FIELD);
        }
    }

    private function updateStatus(Site $site, VehicleTestingStationDto $dto, array & $diff)
    {
        /** @var SiteStatus $old */
        $old = $site->getStatus();
        /** @var string $new */
        $new = $dto->getStatus();

        if(!empty($new) && $new !== $old->getCode()){
            $newStatus = $this->siteStatusRepository->getByCode($new);
            $site->setStatus($newStatus);
            $site->setStatusChangedOn(new \DateTime());
            $this->createDiffArray($diff, $old->getName(), $newStatus->getName(), self::STATUS_FIELD);

        }
    }

    private function updateClasses(Site $site, VehicleTestingStationDto $dto, array & $diff)
    {
        $oldClasses = $site->getApprovedVehicleClasses();
        $new = $dto->getTestClasses();

        if(!empty($new) && $this->isTestClassesModified($oldClasses, $new)) {

            $oldEntities = $site->getApprovedAuthorisationForTestingMotAtSite();
            $stringifyOldClasses = implode(', ', $this->transformClassEntitiesToClassCodesArray($oldClasses));
            $stringifyNewClasses = implode(', ', $new);

            $this->removeTestClasses($site, $oldEntities);
            $this->createAuthorisationForTestingMotAtSite(
                $site,
                $dto,
                AuthorisationForTestingMotAtSiteStatusCode::APPROVED
            );

            $this->createDiffArray($diff, $stringifyOldClasses, $stringifyNewClasses, self::CLASSES_FIELD);
        }
    }

    /**
     * @param AuthorisationForTestingMotAtSite[] $oldClasses
     * @param array $newClasses
     * @return bool
     */
    private function isTestClassesModified(array $oldClasses, array $newClasses)
    {
        $oldClassCodes = $this->transformClassEntitiesToClassCodesArray($oldClasses);

        return $oldClassCodes !== $newClasses;
    }

    /**
     * @param Site $site
     * @param AuthorisationForTestingMotAtSite[] $toRemove
     */
    private function removeTestClasses(Site $site, array $toRemove)
    {
        /** @var AuthorisationForTestingMotAtSite $oldClass */
        foreach($toRemove as $oldClass){
            $site->removeAuthorisationForTestingMotAtSite($oldClass);
            $this->entityManager->remove($oldClass);
        }
        $this->entityManager->persist($site);
    }

    /**
     * @param Site $site
     * @param array $changesDiff
     */
    private function raiseSiteEvents(Site $site, array $changesDiff)
    {
        foreach($changesDiff as $fieldName => $diffArray){

            $this->createSiteEvent(
                $site,
                EventTypeCode::DVSA_ADMINISTRATOR_UPDATE_SITE,
                sprintf(
                    EventDescription::DVSA_ADMINISTRATOR_UPDATE_SITE,
                    $fieldName,
                    $changesDiff[$fieldName][self::OLD_VALUE],
                    $changesDiff[$fieldName][self::NEW_VALUE],
                    $site->getSiteNumber(),
                    $site->getName(),
                    $this->getUserName()
                )
            );
        }
    }

    /**
     * Create site event
     *
     * @param Site $site
     * @param string $eventType
     * @param string $eventDesc
     *
     * @throws \Exception
     */
    private function createSiteEvent(Site $site, $eventType, $eventDesc)
    {
        $event = $this->eventService->addEvent(
            $eventType,
            $eventDesc,
            $this->dateTimeHolder->getCurrent(true)
        );

        $eventMap = (new EventSiteMap())
            ->setEvent($event)
            ->setSite($site);

        $this->entityManager->persist($eventMap);
    }

    /**
     * @param array $diff
     * @param $old
     * @param $new
     * @param $fieldName
     */
    private function createDiffArray(array & $diff, $old, $new, $fieldName)
    {
        $diff[$fieldName] = [
            self::OLD_VALUE => $old,
            self::NEW_VALUE => $new,
        ];
    }

    /**
     * @return integer
     * @description return ID from zend identity
     */
    private function getUserName()
    {
        return $this->identity->getUsername();
    }

    /**
     * Create the Site authorisation and classes in relation to the site
     *
     * @param Site $site
     * @param VehicleTestingStationDto $dto
     * @param string $status
     */
    private function createAuthorisationForTestingMotAtSite(Site $site, VehicleTestingStationDto $dto, $status)
    {
        foreach ($dto->getTestClasses() as $testClass) {
            /** @var VehicleClass $vehicleClass */
            $vehicleClass = $this->vehicleClassRepository->getByCode($testClass);
            /** @var AuthorisationForTestingMotAtSiteStatus $authStatus */
            $authStatus = $this->authForTestingMotStatusRepository->getByCode($status);

            $auth = (new AuthorisationForTestingMotAtSite())
                ->setVehicleClass($vehicleClass)
                ->setSite($site)
                ->setStatus($authStatus);

            $this->entityManager->persist($vehicleClass);
            $this->entityManager->persist($auth);

            $site->addAuthorisationsForTestingMotAtSite($auth);

            $this->entityManager->persist($site);
        }
    }

    /**
     * @param AuthorisationForTestingMotAtSite[] $classEntities
     * @return array Array of vehicle class codes
     */
    private function transformClassEntitiesToClassCodesArray(array $classEntities)
    {
        $classCodeArray = array_map(
            function (VehicleClass $oldClass) {
                return $oldClass->getCode();
            },
            $classEntities
        );

        return $classCodeArray;
    }

}