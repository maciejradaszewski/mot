<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\VehicleClassGroupCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Model\DvsaRole;
use DvsaCommon\Model\TesterAuthorisation;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;

class UpdateMotTestingCertificateAssertion implements AutoWireableInterface
{
    const ERROR_DVSA_USER = "Can not update mot testing certificate for DVSA user";
    const ERROR_TESTER_QUALIFICATION_STATUS= "Can not update mot testing certificate for group %s";
    /**
     * @var MotAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * @var MotIdentityProviderInterface
     */
    private $identityProvider;

    /**
     * @param MotAuthorisationServiceInterface $authorisationService
     */
    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        MotIdentityProviderInterface $identityProvider
    ) {
        $this->authorisationService = $authorisationService;
        $this->identityProvider = $identityProvider;
    }

    public function isGranted($personId, $vehicleClassGroupCode, array $personSystemRoles, TesterAuthorisation $personAuthorisation)
    {
        try {
            $this->assertGranted($personId, $vehicleClassGroupCode, $personSystemRoles, $personAuthorisation);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    public function assertGranted($personId, $vehicleClassGroupCode, array $personSystemRoles, TesterAuthorisation $personAuthorisation)
    {
        if (!$this->isUpdatingOwnCertificate($personId)) {
            $this->authorisationService->assertGranted(PermissionInSystem::UPDATE_MOT_TESTING_CERTIFICATE_FOR_USER);
        }

        if ($this->hasDvsaRole($personSystemRoles)) {
            throw new UnauthorisedException(self::ERROR_DVSA_USER);
        }

        if (!$this->hasCorrectQualificationStatus($personId, $vehicleClassGroupCode, $personAuthorisation)) {
            throw new UnauthorisedException(sprintf(self::ERROR_TESTER_QUALIFICATION_STATUS, $vehicleClassGroupCode));
        }
    }

    private function hasDvsaRole(array $personSystemRoles)
    {
        foreach ($personSystemRoles as $role) {
            if (DvsaRole::isDvsaRole($role)) {
                return true;
            }
        }

        return false;
    }

    private function hasCorrectQualificationStatus($personId, $vehicleClassGroupCode, TesterAuthorisation $personAuthorisation)
    {
        if ($vehicleClassGroupCode === VehicleClassGroupCode::BIKES && $personAuthorisation->hasGroupAStatus()) {
            $status = $personAuthorisation->getGroupAStatus();
        } elseif($vehicleClassGroupCode === VehicleClassGroupCode::CARS_ETC && $personAuthorisation->hasGroupBStatus()) {
            $status = $personAuthorisation->getGroupBStatus();
        } else {
            return false;
        }

        $motStatuses = [];
        if ($this->isUpdatingOwnCertificate($personId)) {
            $motStatuses[] = AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED;
        } else {
            $motStatuses[] = AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED;
            $motStatuses[] = AuthorisationForTestingMotStatusCode::QUALIFIED;

        }

        return in_array($status->getCode(), $motStatuses);
    }

    private function isUpdatingOwnCertificate($personId)
    {
        return $this->identityProvider->getIdentity()->getUserId() === $personId;
    }
}
