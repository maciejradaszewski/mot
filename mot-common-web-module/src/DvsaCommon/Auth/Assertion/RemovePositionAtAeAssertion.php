<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Domain\MotTestType;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Exception\UnauthorisedException;

class RemovePositionAtAeAssertion
{
    private $authorisationService;
    private $identityProvider;

    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        MotIdentityProviderInterface $identityProvider
    ) {
        $this->authorisationService = $authorisationService;
        $this->identityProvider = $identityProvider;
    }

    public function isGranted($roleName, $personId, $aeId)
    {
        try {
            $this->assertGranted($roleName, $personId, $aeId);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    public function assertGranted($roleName, $personId, $aeId)
    {
        if ($roleName == OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER)
        {
            $this->assertGrantedForAedmPosition($aeId);
        }else
        {
            $this->assertGrantedForOtherPositions($personId, $aeId);
        }
    }

    private function assertGrantedForAedmPosition($aeId)
    {
        $this->authorisationService->assertGrantedAtOrganisation(PermissionAtOrganisation::REMOVE_AEDM_FROM_AE, $aeId);
    }

    private function assertGrantedForOtherPositions($personId, $aeId)
    {
        if ($this->identityProvider->getIdentity()->getUserId() == $personId)
        {
            return;
        }

        $this->authorisationService->assertGrantedAtOrganisation(PermissionAtOrganisation::REMOVE_POSITION_FROM_AE, $aeId);

    }
}
