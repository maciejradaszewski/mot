<?php

namespace OrganisationApi\Model\Operation;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\RoleCode;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use NotificationApi\Service\BusinessLogic\PositionInOrganisationNominationHandler;
use NotificationApi\Service\NotificationService;
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
    private $notificationService;

    public function __construct(
        EntityManager $entityManager,
        NominationVerifier $nominationVerifier,
        OrganisationNominationService $organisationNominationService,
        NotificationService $notificationService
    ) {
        $this->entityManager                 = $entityManager;
        $this->nominationVerifier            = $nominationVerifier;
        $this->organisationNominationService = $organisationNominationService;
        $this->notificationService = $notificationService;
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

        $this->entityManager->persist($nomination);
        $this->entityManager->flush();

        $notificationId = $this->organisationNominationService->sendNomination($nominator, $nomination);

        $roleName = $nomination->getOrganisationBusinessRole()->getRole();

        if ($roleName->getCode() == RoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER) {
            $this->notificationService->action(
                $notificationId,
                [
                    'action' =>  PositionInOrganisationNominationHandler::ACCEPTED
                ]
            );
        }

        return $nomination;
    }
}
