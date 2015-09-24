<?php

namespace OrganisationApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\EventDescription;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\EventOrganisationMap;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\OrganisationRepository;
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
        DateTimeHolder $dateTimeHolder
    ) {
        parent::__construct($entityManager);

        $this->authService = $authService;
        $this->identity = $motIdentity;
        $this->eventService = $eventService;
        $this->organisationRepository = $organisationRepository;
        $this->authForAeStatusRepository = $authForAeStatusRepository;
        $this->xssFilter = $xssFilter;
        $this->validator = $validator;

        $this->dateTimeHolder = $dateTimeHolder;
    }

    public function updateStatus($id, OrganisationDto $dto)
    {
        $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $id);

        /** @var OrganisationDto $dto */
        $dto = $this->xssFilter->filter($dto);

        $this->validator->validateStatus($dto->getAuthorisedExaminerAuthorisation());

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
        $authorisedExaminer
            ->setValidFrom($this->dateTimeHolder->getCurrentDate())
            ->setStatus($status);

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
}
