<?php

namespace DvsaMotApi\Service\ReplacementCertificate;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use Api\Check\CheckResultExceptionTranslator;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\ReplacementCertificateDraft;
use DvsaMotApi\Service\MotTestSecurityService;
use Zend\Authentication\AuthenticationService;

/**
 * Class ReplacementCertificateUpdater
 *
 * @package DvsaMotApi\Service\ReplacementCertificate
 */
class ReplacementCertificateUpdater
{
    private $motTestSecurityService;

    /** @var AuthorisationServiceInterface $authService */
    private $authService;

    /** @var AuthenticationService $motIdentityProvider */
    private $motIdentityProvider;

    /**
     * @param MotTestSecurityService $motTestSecurityService
     * @param AuthorisationServiceInterface $authService
     * @param AuthenticationService $motIdentityProvider
     */
    public function __construct(
        MotTestSecurityService $motTestSecurityService,
        AuthorisationServiceInterface $authService,
        AuthenticationService $motIdentityProvider
    ) {
        $this->motTestSecurityService = $motTestSecurityService;
        $this->authService = $authService;
        $this->motIdentityProvider = $motIdentityProvider;
    }

    /**
     * @param ReplacementCertificateDraft $draft
     *
     * @return MotTest
     * @throws ForbiddenException
     */
    public function update(ReplacementCertificateDraft $draft)
    {
        $hasFullRights = $this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS);

        $motTest = $draft->getMotTest();
        if ($motTest->getVersion() !== $draft->getMotTestVersion()) {
            throw new ForbiddenException(
                "A previous change to certificate has been detected.
                Please try to edit the certificate again"
            );
        }
        if (!$hasFullRights) {
            $checkResult = $this->motTestSecurityService->validateOdometerReadingModificationWindowOpen($motTest);
            CheckResultExceptionTranslator::tryThrowBadRequestException($checkResult);

            if (!$this->motTestSecurityService->isCurrentTesterAssignedToMotTest($motTest)
                && !$draft->getReasonForDifferentTester()
            ) {
                throw new ForbiddenException("Reason for different tester expected");
            }
        } else {
            if (!$draft->getReplacementReason()) {
                throw new ForbiddenException("Reason for replacement expected");
            }
        }

        $this->updateMotTestFromDraft($draft, $motTest, $hasFullRights);
        if($motTest->getPrsMotTest()){
            $motTest->setPrsMotTest($this->updateMotTestFromDraft($draft, $motTest->getPrsMotTest(), $hasFullRights));
        }

        return $motTest;
    }

    /**
     * @param ReplacementCertificateDraft $draft
     * @param $motTest
     * @param $hasFullRights
     * @return MotTest
     */
    protected function updateMotTestFromDraft(ReplacementCertificateDraft $draft, MotTest $motTest, $hasFullRights)
    {
        $motTest->setOdometerReading($draft->getOdometerReading())
            ->setExpiryDate($draft->getExpiryDate())
            ->setPrimaryColour($draft->getPrimaryColour())
            ->setSecondaryColour($draft->getSecondaryColour());

        $vehicle = $motTest->getVehicle();

        $vehicle->setColour($draft->getPrimaryColour())
            ->setSecondaryColour($draft->getSecondaryColour());

        if ($hasFullRights) {
            $motTest
                ->setVehicleTestingStation($draft->getVehicleTestingStation())
                ->setVin($draft->getVin())
                ->setRegistration($draft->getVrm())
                ->setModel($draft->getModel())
                ->setFreeTextModelName($draft->getModel() ? null : $vehicle->getModelName())
                ->setMake($draft->getMake())
                ->setFreeTextMakeName($draft->getMake() ? null : $vehicle->getMakeName())
                ->setCountryOfRegistration($draft->getCountryOfRegistration())
                ->setEmptyVinReason(null)
                ->setEmptyVrmReason(null);

            if ($draft->getMakeName()) {
                $motTest->setMake(null);
                $motTest->setFreeTextMakeName($draft->getMakeName());
            }

            if ($draft->getModelName()) {
                $motTest->setModel(null);
                $motTest->setFreeTextModelName($draft->getModelName());
            }

            $vehicle->setVin($draft->getVin())
                ->setRegistration($draft->getVrm())
                ->setCountryOfRegistration($draft->getCountryOfRegistration())
                ->setEmptyVinReason(null)
                ->setEmptyVrmReason(null);

            if (!$draft->getMakeName() && !$draft->getModelName()) {
                $vehicle->setMake($draft->getMake())->setModel($draft->getModel());
            }
        }

        return $motTest;
    }
}
