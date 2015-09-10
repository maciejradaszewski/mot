<?php

namespace OrganisationApi\Model\Operation;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Service\OrganisationNominationService;
use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Constants\EventDescription;
use DvsaEntities\Entity\EventPersonMap;

/**
 * Class DirectNominationOperation
 *
 * Assigns a role to person.
 * Nominee does not need to accept the nomination.
 * He/she gets the role immediately.
 *
 * @package OrganisationApi\Model\Operation
 */
class DirectNominationOperation implements NominateOperationInterface
{

    private $entityManager;
    private $nominationVerifier;
    private $organisationNominationService;
    private $eventService;
    private $dateTimeHolder;

    public function __construct(
        EntityManager $entityManager,
        NominationVerifier $nominationVerifier,
        OrganisationNominationService $organisationNominationService,
        EventService $eventService,
        DateTimeHolder $dateTimeHolder
    ) {
        $this->entityManager                 = $entityManager;
        $this->nominationVerifier            = $nominationVerifier;
        $this->organisationNominationService = $organisationNominationService;
        $this->eventService = $eventService;
        $this->dateTimeHolder = $dateTimeHolder;
    }

    /**
     * @param Person                      $nominator
     * @param OrganisationBusinessRoleMap $nomination
     *
     * @return OrganisationBusinessRoleMap
     */
    public function nominate(Person $nominator, OrganisationBusinessRoleMap $nomination)
    {
        $this->nominationVerifier->verify($nomination);

        /** @var BusinessRoleStatus $businessRoleStatus */
        $businessRoleStatus = $this->entityManager->getRepository(BusinessRoleStatus::class)->findOneBy(
            [
                'code' => BusinessRoleStatusCode::ACTIVE,
            ]
        );
        $nomination->setBusinessRoleStatus($businessRoleStatus);

        $event = $this->setEvent($nomination);

        $this->entityManager->persist($nomination);
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        $this->organisationNominationService->sendNotification($nominator, $nomination);

        return $nomination;
    }

    private function setEvent(OrganisationBusinessRoleMap $nomination)
    {
        $positionName = $nomination->getOrganisationBusinessRole()->getFullName();
        $organisationId  = $nomination->getOrganisation()->getAuthorisedExaminer()->getNumber();
        $organisationName = $nomination->getOrganisation()->getName();

        $event = $this->eventService->addEvent(
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            sprintf(
                EventDescription::ROLE_ASSOCIATION_CHANGE,
                $positionName,
                $organisationId,
                $organisationName
            ),
            $this->dateTimeHolder->getCurrent(true)
        );

        $eventPersonMap = new EventPersonMap();
        $eventPersonMap->setEvent($event)
                       ->setPerson($nomination->getPerson());

        return $eventPersonMap;
    }
}
