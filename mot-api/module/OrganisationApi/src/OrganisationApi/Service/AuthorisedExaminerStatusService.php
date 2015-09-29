<?php

namespace OrganisationApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\EventDescription;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\EventOrganisationMap;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;

class AuthorisedExaminerStatusService extends AbstractService
{
    const FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME = 'isCorrespondenceContactDetailsSame';
    const FIELD_AREA_OFFICE_NUMBER = 'areaOfficeNumber';

    /**
     * @var AuthorisationServiceInterface
     */
    private $authService;
    /**
     * @var MotIdentityInterface
     */
    private $identity;
    /**
     * @var EventService $eventService
     */
    private $eventService;
    /**
     * @var OrganisationRepository
     */
    private $organisationRepository;
    /**
     * @var SiteRepository
     */
    private $siteRepository;
    /**
     * @var \DvsaCommonApi\Filter\XssFilter
     */
    protected $xssFilter;
    /**
     * @var AuthForAeStatusRepository
     */
    private $authForAeStatusRepository;
    /**
     * @var DateTimeHolder
     */
    private $dateTimeHolder;
    /**
     * @var AuthorisedExaminerValidator
     */
    private $validator;

    /**
     * @param EntityManager $entityManager
     * @param AuthorisationServiceInterface $authService
     * @param MotIdentityInterface $motIdentity
     * @param EventService $eventService
     * @param OrganisationRepository $organisationRepository
     * @param AuthForAeStatusRepository $authForAeStatusRepository
     * @param XssFilter $xssFilter
     * @param AuthorisedExaminerValidator $validator
     * @param DateTimeHolder $dateTimeHolder
     * @param SiteRepository $siteRepository
     */
    public function __construct(
        EntityManager $entityManager,
        AuthorisationServiceInterface $authService,
        MotIdentityInterface $motIdentity,
        EventService $eventService,
        OrganisationRepository $organisationRepository,
        AuthForAeStatusRepository $authForAeStatusRepository,
        XssFilter $xssFilter,
        AuthorisedExaminerValidator $validator,
        DateTimeHolder $dateTimeHolder,
        SiteRepository $siteRepository
    ) {
        parent::__construct($entityManager);

        $this->authService = $authService;
        $this->identity = $motIdentity;
        $this->eventService = $eventService;
        $this->organisationRepository = $organisationRepository;
        $this->authForAeStatusRepository = $authForAeStatusRepository;
        $this->siteRepository = $siteRepository;
        $this->xssFilter = $xssFilter;
        $this->validator = $validator;

        $this->dateTimeHolder = $dateTimeHolder;
    }

    public function updateStatus($id, OrganisationDto $dto)
    {
        $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $id);

        /** @var OrganisationDto $dto */
        $dto = $this->xssFilter->filter($dto);

        $allAreaOffices = $this->siteRepository->getAllAreaOffices();
        $dtoAuthExaminer = $dto->getAuthorisedExaminerAuthorisation();
        $this->validator->validateStatus($dtoAuthExaminer);
        $this->validator->validateAreaOffice(
            $dtoAuthExaminer,
            $allAreaOffices
        );

        $this->validator->failOnErrors();

        if ($dto->isValidateOnly() === true) {
            return null;
        }

        $organisation = $this->organisationRepository->getAuthorisedExaminer($id);

        /** @var AuthForAeStatus $status */
        $status = $this->authForAeStatusRepository->getByCode(
            $dto->getAuthorisedExaminerAuthorisation()->getStatus()->getCode()
        );

        $oldStatus = $organisation->getAuthorisedExaminer()->getStatus()->getName();
        $authorisedExaminer = $organisation->getAuthorisedExaminer();

        // Wire in the (possibly) changed Area Office entity
        /** @var AuthorisedExaminerAuthorisationDto $authForAe */
        $authForAe = $dto->getAuthorisedExaminerAuthorisation();

        if ($authForAe) {
            $newAOId = $this->getAreaOfficeIdByNumber($authForAe->getAssignedAreaOffice(), $allAreaOffices);
            $newAO = $this->siteRepository->find($newAOId);

            $authorisedExaminer
                ->setValidFrom($this->dateTimeHolder->getCurrentDate())
                ->setStatusChangedOn($this->dateTimeHolder->getCurrentDate())
                ->setStatus($status)
                ->setAreaOffice($newAO);
        }

        //  logical block :: create event
        $event = $this->eventService->addEvent(
            EventTypeCode::UPDATE_AE,
            sprintf(
                EventDescription::DVSA_ADMINISTRATOR_UPDATE_AE_STATUS,
                $oldStatus,
                $status->getName(),
                $authorisedExaminer->getNumber(),
                $organisation->getName(),
                $this->identity->getUsername()
            ),
            $this->dateTimeHolder->getCurrent(true)
        );

        $eventMap = (new EventOrganisationMap())
            ->setEvent($event)
            ->setOrganisation($organisation);

        //  logical block :: store in db
        $this->entityManager->persist($authorisedExaminer);
        $this->entityManager->persist($eventMap);
        $this->entityManager->flush();

        return ['id' => $organisation->getId()];
    }


    private function getAreaOfficeIdByNumber($aoNumber, $allAreaOffices)
    {
        $aoNumber = (int)$aoNumber;

        foreach ($allAreaOffices as $areaOffice) {
            if ($aoNumber == $areaOffice['areaOfficeNumber']) {
                return $areaOffice['id'];
            }
        }
        return null;
    }

    /**
     * This answers a list of all of the area offices currently active in the system.
     * The returned structure contains a LIST of PROPERTIES, as the list of Area Office
     * numbers is not contiguous.
     *
     * @return array
     */
    public function getAllAreaOffices()
    {
        return $this->siteRepository->getAllAreaOffices();
    }
}
