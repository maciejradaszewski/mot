<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Domain\MotTestType;
use DvsaCommon\Exception\UnauthorisedException;

class PerformMotTestAssertion
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

    public function isGranted($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        try {
            $this->assertGranted($motTestTypeCode, $motTestOwnerId, $vtsId);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    public function assertGranted($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        if (MotTestType::isStandard($motTestTypeCode)) {
            $this->assertGrantedForStandardMotTest($motTestTypeCode, $motTestOwnerId, $vtsId);
        } elseif (MotTestType::isDemo($motTestTypeCode)) {
            $this->assertGrantedForDemoTest($motTestTypeCode, $motTestOwnerId, $vtsId);
        } elseif (MotTestType::isReinspection($motTestTypeCode)) {
            $this->assertGrantedForReinspection($motTestTypeCode, $motTestOwnerId, $vtsId);
        } elseif (MotTestType::isNonMotTypes($motTestTypeCode)) {
            $this->assertGrantedForNonMotTest($motTestTypeCode, $motTestOwnerId, $vtsId);
        } else {
            throw new \Exception('Unknown test type');
        }
    }

    private function assertGrantedForStandardMotTest($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        $this->authorisationService->assertGranted(PermissionInSystem::MOT_TEST_PERFORM);
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::MOT_TEST_PERFORM_AT_SITE, $vtsId);
        $this->assertIsMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId);
    }

    private function assertGrantedForDemoTest($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        $this->assertIsMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId);
    }

    private function assertGrantedForReinspection($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        $this->authorisationService->assertGranted(PermissionInSystem::MOT_TEST_REINSPECTION_PERFORM);
        $this->assertIsMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId);
    }

    private function assertGrantedForNonMotTest($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        $this->authorisationService->assertGranted(PermissionInSystem::MOT_TEST_REINSPECTION_PERFORM);
        $this->assertIsMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId);
    }

    private function assertIsMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId = 0)
    {
        if (!$this->isMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId)) {
            throw new UnauthorisedException('Mot test owner assertion failed.');
        }
    }

    private function isMotTestOwner($motTestTypeCode, $motTestOwnerId, $vtsId)
    {
        return $this->identityProvider->getIdentity()->getUserId() == $motTestOwnerId;
    }
}
