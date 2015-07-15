<?php

namespace Organisation\Authorisation;

use DvsaCommon\Auth\Assertion\RemovePositionAtAeAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Dto\Organisation\OrganisationPositionDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Dto\Person\PersonDto;

class AuthorisedExaminerViewAuthorisation
{
    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;
    /** @var int */
    private $authorisedExaminerId;
    /** @var MotIdentityProviderInterface */
    private $identityProvider;
    /** @var RemovePositionAtAeAssertion */
    private $removePositionAssertion;
    /** @var OrganisationPositionDto[] */
    private $positions;

    /**
     * @param MotAuthorisationServiceInterface $authorisationService
     * @param MotIdentityProviderInterface $identityProvider
     * @param int $authorisedExaminerId
     */
    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        MotIdentityProviderInterface $identityProvider,
        $authorisedExaminerId
    ) {
        $this->authorisationService = $authorisationService;
        $this->authorisedExaminerId = $authorisedExaminerId;
        $this->identityProvider = $identityProvider;

        $this->removePositionAssertion = new RemovePositionAtAeAssertion($authorisationService, $identityProvider);
    }

    /**
     * @param OrganisationPositionDto[] $positions
     */
    public function setPositions(array $positions)
    {
        $this->positions = $positions;
    }

    public function canViewAuthorisedExaminerPrincipals()
    {
        $permission = PermissionAtOrganisation::LIST_AEP_AT_AUTHORISED_EXAMINER;
        return $this->authorisationService->isGrantedAtOrganisation($permission, $this->authorisedExaminerId);
    }

    public function canCreateAuthorisedExaminerPrincipal()
    {
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_CREATE;
        return $this->authorisationService->isGrantedAtOrganisation($permission, $this->authorisedExaminerId);
    }

    public function canRemoveAuthorisedExaminerPrincipal()
    {
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_REMOVE;
        return $this->authorisationService->isGrantedAtOrganisation($permission, $this->authorisedExaminerId);
    }

    public function canUpdateAe()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $this->authorisedExaminerId
        );
    }

    public function canViewVtsList()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::VEHICLE_TESTING_STATION_LIST_AT_AE, $this->authorisedExaminerId
        );
    }

    public function canViewVts($id)
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::VEHICLE_TESTING_STATION_READ, $id);
    }

    public function canRemovePosition(OrganisationPositionDto $position)
    {
        return $this->removePositionAssertion->isGranted(
            $position->getRole(),
            $position->getPerson()->getId(),
            $this->authorisedExaminerId
        );
    }

    public function canViewPersonnel()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::LIST_AE_POSITIONS, $this->authorisedExaminerId
        );
    }

    public function canNominate()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::NOMINATE_ROLE_AT_AE, $this->authorisedExaminerId
        );
    }

    public function canBuySlots()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::SLOTS_PURCHASE, $this->authorisedExaminerId
        );
    }

    public function canViewTransactionHistory()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::SLOTS_TRANSACTION_READ_FULL, $this->authorisedExaminerId
        );
    }

    public function canViewSlotUsage()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_SLOTS_USAGE_READ, $this->authorisedExaminerId
        );
    }

    public function canViewTestLogs()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_TEST_LOG, $this->authorisedExaminerId
        );
    }

    public function canSetDirectDebit()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::SLOTS_PAYMENT_DIRECT_DEBIT, $this->authorisedExaminerId
        );
    }

    public function canRefund()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::SLOTS_REFUND);
    }

    public function canSettlePayment()
    {
        return $this->authorisationService->isGranted(
            PermissionAtOrganisation::SLOTS_INSTANT_SETTLEMENT
        );
    }

    public function canViewSlotBalance()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_SLOTS_BALANCE_READ, $this->authorisedExaminerId
        );
    }

    public function canAdjustSlotBalance()
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::SLOTS_ADJUSTMENT, $this->authorisedExaminerId
        );
    }

    public function canViewSlotsSection()
    {
        return $this->canBuySlots()
            || $this->canViewTransactionHistory()
            || $this->canViewSlotUsage()
            || $this->canViewTestLogs()
            || $this->canSetDirectDebit()
            || $this->canSettlePayment()
            || $this->canViewSlotBalance();
    }

    public function canViewAeStatus()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL);
    }

    public function canSearchAe()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_LIST);
    }

    public function canSearchUser()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::USER_SEARCH);
    }

    public function canViewProfile(PersonDto $person)
    {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_EMPLOYEE_PROFILE_READ,
            $this->authorisedExaminerId
        )
        && $this->personIsEmployee($person);
    }

    private function personIsEmployee(PersonDto $person)
    {
        return ArrayUtils::anyMatch(
            $this->positions,
            function (OrganisationPositionDto $position) use ($person) {
                return $position->getPerson()->getId() == $person->getId()
                && $position->isActive();
            }
        );
    }
}
