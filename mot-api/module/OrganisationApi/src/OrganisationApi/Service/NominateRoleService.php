<?php

namespace OrganisationApi\Service;

use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Database\Transaction;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommon\Enum\RoleCode;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\OrganisationPositionHistory;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\OrganisationPositionHistoryRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\PersonRepository;
use OrganisationApi\Model\Operation\DirectNominationOperation;
use OrganisationApi\Model\Operation\NominateByRequestOperation;
use OrganisationApi\Model\Operation\NominateOperationInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class NominateRoleService
 *
 * @package OrganisationApi\Service
 */
class NominateRoleService
{

    private $personRepository;
    private $organisationRepository;
    private $organisationPositionHistoryRepository;
    private $organisationBusinessRoleRepository;
    private $authorisationService;
    private $nominateOperation;
    private $assignRoleOperation;
    private $transaction;

    /** @var AuthenticationService $motIdentityProvider */
    private $motIdentityProvider;

    private $businessRoleStatusRepository;

    public function __construct(
        OrganisationRepository $organisationRepository,
        OrganisationPositionHistoryRepository $organisationPositionHistoryRepository,
        PersonRepository $personRepository,
        EntityRepository $organisationBusinessRoleRepository,
        EntityRepository $businessRoleStatusRepository,
        AuthorisationServiceInterface $authorisationService,
        NominateByRequestOperation $nominateOperation,
        DirectNominationOperation $assignRoleOperation,
        Transaction $transaction,
        AuthenticationService $motIdentityProvider
    ) {
        $this->organisationRepository                = $organisationRepository;
        $this->organisationPositionHistoryRepository = $organisationPositionHistoryRepository;
        $this->personRepository                      = $personRepository;
        $this->organisationBusinessRoleRepository    = $organisationBusinessRoleRepository;
        $this->authorisationService                  = $authorisationService;
        $this->nominateOperation                     = $nominateOperation;
        $this->assignRoleOperation                   = $assignRoleOperation;
        $this->transaction                           = $transaction;
        $this->authorisationService                  = $authorisationService;
        $this->motIdentityProvider                   = $motIdentityProvider;
        $this->businessRoleStatusRepository          = $businessRoleStatusRepository;
    }

    /**
     * @param $organisationId
     * @param $nomineeId
     * @param $roleId
     *
     * @return OrganisationBusinessRoleMap
     */
    public function nominateRole($organisationId, $nomineeId, $roleId)
    {
        $this->authorisationService->assertGrantedAtOrganisation(PermissionAtOrganisation::NOMINATE_ROLE_AT_AE, $organisationId);

        $nominator  = $this->getNominator();
        $nomination = $this->getNomination($nomineeId, $roleId, $organisationId);

        $businessRole = $nomination->getOrganisationBusinessRole()->getRole();

        if ($businessRole->getCode() == RoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER) {
            $nominationOperation = $this->assignRoleOperation;
        } else {
            $nominationOperation =  $this->nominateOperation;
        }

        $organisationPosition = $nominationOperation->nominate($nominator, $nomination);

        $this->transaction->flush();

        return $organisationPosition;
    }

    private function getNomination($nomineeId, $roleId, $organisationId)
    {
        $role         = $this->getRole($roleId);
        $organisation = $this->getOrganisation($organisationId);
        $nominee      = $this->getNominee($nomineeId);

        $status = $this->businessRoleStatusRepository->findOneBy(
            ['code' => BusinessRoleStatusCode::PENDING]
        );

        $map = new OrganisationBusinessRoleMap();
        $map->setPerson($nominee)
            ->setOrganisationBusinessRole($role)
            ->setOrganisation($organisation)
            ->setBusinessRoleStatus($status);

        return $map;
    }

    private function getNominator()
    {
        $personId = $this->motIdentityProvider->getIdentity()->getUserId();

        return $this->personRepository->get($personId);
    }

    private function getNominee($nomineeId)
    {
        return $this->personRepository->get($nomineeId);
    }

    private function getRole($roleId)
    {
        $role = $this->organisationBusinessRoleRepository->findOneBy(
            ['id' => $roleId]
        );

        return $role;
    }

    private function getOrganisation($organisationId)
    {
        return $this->organisationRepository->get($organisationId);
    }
}
