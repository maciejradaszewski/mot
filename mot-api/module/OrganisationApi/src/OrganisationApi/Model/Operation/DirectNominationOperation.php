<?php

namespace OrganisationApi\Model\Operation;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Service\OrganisationNominationService;

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

    public function __construct(
        EntityManager $entityManager,
        NominationVerifier $nominationVerifier,
        OrganisationNominationService $organisationNominationService
    ) {
        $this->entityManager                 = $entityManager;
        $this->nominationVerifier            = $nominationVerifier;
        $this->organisationNominationService = $organisationNominationService;
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

        $this->entityManager->persist($nomination);
        $this->entityManager->flush($nomination);

        $this->organisationNominationService->sendNotification($nominator, $nomination);

        return $nomination;
    }
}
