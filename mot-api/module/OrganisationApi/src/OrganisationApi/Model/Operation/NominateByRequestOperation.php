<?php

namespace OrganisationApi\Model\Operation;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Service\OrganisationNominationService;

/**
 * Class NominateByRequestOperation
 *
 * Sends a role nomination to a person.
 * Nominee will need to accept the nomination request first.
 * Before that he cannot fulfill that role.
 *
 * @package OrganisationApi\Model\Operation
 */
class NominateByRequestOperation implements NominateOperationInterface
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
     * Creates link between Pers
     *
     * @param Person                      $nominator
     * @param OrganisationBusinessRoleMap $nomination
     *
     * @return OrganisationBusinessRoleMap
     */
    public function nominate(Person $nominator, OrganisationBusinessRoleMap $nomination)
    {
        $this->nominationVerifier->verify($nomination);

        $this->entityManager->persist($nomination);
        $this->entityManager->flush($nomination);

        $this->organisationNominationService->sendNomination($nominator, $nomination);

        return $nomination;
    }
}
