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

class CreateMotTestingCertificateAssertion implements AutoWireableInterface
{
    const ERROR_DVSA_USER = "Can not create mot testing certificate for DVSA user";
    const ERROR_TESTER_QUALIFICATION_STATUS= "Can not create mot testing certificate for group %s";
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
        if ($this->identityProvider->getIdentity()->getUserId() !== $personId) {
            $this->authorisationService->assertGranted(PermissionInSystem::CREATE_MOT_TESTING_CERTIFICATE_FOR_USER);
        }

        if ($this->hasDvsaRole($personSystemRoles)) {
            throw new UnauthorisedException(self::ERROR_DVSA_USER);
        }

        if (!$this->hasCorrectQualificationStatus($vehicleClassGroupCode, $personAuthorisation)) {
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

    private function hasCorrectQualificationStatus($vehicleClassGroupCode, TesterAuthorisation $personAuthorisation)
    {
        $statusCode = null;
        if ($vehicleClassGroupCode === VehicleClassGroupCode::BIKES && $personAuthorisation->hasGroupAStatus()) {
            $statusCode = $personAuthorisation->getGroupAStatus()->getCode();
        } elseif($vehicleClassGroupCode === VehicleClassGroupCode::CARS_ETC && $personAuthorisation->hasGroupBStatus()) {
            $statusCode = $personAuthorisation->getGroupBStatus()->getCode();
        }

        if ($statusCode === null || $statusCode === AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED) {
            return true;
        }

        return false;
    }
}
