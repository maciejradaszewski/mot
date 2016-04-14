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

class RemoveMotTestingCertificateAssertion implements AutoWireableInterface
{
    const ERROR_TESTER_QUALIFICATION_STATUS= "Can not remove mot testing certificate for group %s";

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

    public function isGranted($personId, $vehicleClassGroupCode, TesterAuthorisation $personAuthorisation)
    {
        try {
            $this->assertGranted($personId, $vehicleClassGroupCode, $personAuthorisation);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    public function assertGranted($personId, $vehicleClassGroupCode, TesterAuthorisation $personAuthorisation)
    {
        if (!$this->isUpdatingOwnCertificate($personId)) {
            $this->authorisationService->assertGranted(PermissionInSystem::REMOVE_MOT_TESTING_CERTIFICATE_FOR_USER);
        }

        if (!$this->hasCorrectQualificationStatus($personId, $vehicleClassGroupCode, $personAuthorisation)) {
            throw new UnauthorisedException(sprintf(self::ERROR_TESTER_QUALIFICATION_STATUS, $vehicleClassGroupCode));
        }
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

        $allowedStatuses = [
            AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED,
            AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED
        ];

        return in_array($status->getCode(), $allowedStatuses);
    }

    private function isUpdatingOwnCertificate($personId)
    {
        return $this->identityProvider->getIdentity()->getUserId() === $personId;
    }
}
