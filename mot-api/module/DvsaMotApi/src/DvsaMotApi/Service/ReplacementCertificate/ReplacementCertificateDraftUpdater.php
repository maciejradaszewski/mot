<?php

namespace DvsaMotApi\Service\ReplacementCertificate;

use DataCatalogApi\Service\VehicleCatalogService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateUtils;
use Api\Check\CheckResultExceptionTranslator;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Entity\ReplacementCertificateDraft;
use DvsaEntities\Repository\CertificateChangeReasonRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;
use DvsaMotApi\Helper\Odometer\OdometerHolderUpdater;
use DvsaMotApi\Service\MotTestSecurityService;
use DvsaMotApi\Service\Validator\ReplacementCertificateDraftChangeValidator;
use Zend\Authentication\AuthenticationService;

/**
 * Class ReplacementCertificateDraftUpdater
 *
 * @package DvsaMotApi\Service\ReplacementCertificate
 */
class ReplacementCertificateDraftUpdater implements TransactionAwareInterface
{

    use TransactionAwareTrait;

    /**
     * @var AuthorisationServiceInterface $authorizationService
     */
    private $authorizationService;

    /** @var  VehicleCatalogService */
    private $vehicleCatalog;

    /**
     * @var  MotTestSecurityService $motTestSecurityService
     */
    private $motTestSecurityService;

    /**
     * @var \DvsaEntities\Repository\SiteRepository $vtsRepository
     */
    private $vtsRepository;

    /**
     * @var \DvsaEntities\Repository\CertificateChangeReasonRepository $certificateChangeReasonRepository
     */
    private $certificateChangeReasonRepository;

    /** @var AuthenticationService $motIdentityProvider */
    private $motIdentityProvider;

    /**
     * @var \DvsaMotApi\Helper\Odometer\OdometerHolderUpdater
     */
    private $odometerHolderUpdater;

    /**
     * @param MotTestSecurityService                     $motTestSecurityService
     * @param AuthorisationServiceInterface              $authorizationService
     * @param VehicleCatalogService                      $vehicleCatalog
     * @param CertificateChangeReasonRepository          $certificateChangeReasonRepository
     * @param SiteRepository                             $vtsRepository
     * @param AuthenticationService                      $motIdentityProvider
     * @param ReplacementCertificateDraftChangeValidator $replacementCertificateDraftChangeValidator
     * @param OdometerHolderUpdater                      $odometerHolderUpdater
     */
    public function __construct(
        MotTestSecurityService $motTestSecurityService,
        AuthorisationServiceInterface $authorizationService,
        VehicleCatalogService $vehicleCatalog,
        CertificateChangeReasonRepository $certificateChangeReasonRepository,
        SiteRepository $vtsRepository,
        AuthenticationService $motIdentityProvider,
        ReplacementCertificateDraftChangeValidator $replacementCertificateDraftChangeValidator,
        OdometerHolderUpdater $odometerHolderUpdater
    ) {
        $this->motTestSecurityService = $motTestSecurityService;
        $this->authorizationService = $authorizationService;
        $this->vehicleCatalog = $vehicleCatalog;
        $this->vtsRepository = $vtsRepository;
        $this->certificateChangeReasonRepository = $certificateChangeReasonRepository;
        $this->motIdentityProvider = $motIdentityProvider;
        $this->replacementCertificateDraftChangeValidator = $replacementCertificateDraftChangeValidator;
        $this->odometerHolderUpdater = $odometerHolderUpdater;
    }

    /**
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $draftChange
     *
     * @return ReplacementCertificateDraft
     * @throws \DvsaCommonApi\Service\Exception\ForbiddenException
     */
    public function updateDraft(ReplacementCertificateDraft $draft, ReplacementCertificateDraftChangeDTO $draftChange)
    {
        $validationResult = $this->replacementCertificateDraftChangeValidator->validate($draftChange);
        CheckResultExceptionTranslator::tryThrowBadRequestException($validationResult);

        $this->inTransaction(
            function () use (&$draft, &$draftChange) {
                $this->applyChanges($draft, $draftChange);
            }
        );

        return $draft;
    }

    private function applyChanges(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $draftChange
    ) {
        if ($draftChange->isOdometerReadingSet()) {
            $this->odometerHolderUpdater->update($draft, $draftChange->getOdometerReading());
        }
        if ($draftChange->isPrimaryColourSet()) {
            $draft->setPrimaryColour($this->vehicleCatalog->getColourByCode($draftChange->getPrimaryColour()));
        }
        if ($draftChange->isSecondaryColourSet()) {
            $colour = $draftChange->getSecondaryColour() === null
                ? null
                :
                $this->vehicleCatalog->getColourByCode($draftChange->getSecondaryColour());
            $draft->setSecondaryColour($colour);
        }

        $draft = $this->applyChangesSpecialFields($draft, $draftChange);

        if ($draftChange->isReasonForDifferentTesterSet()) {
            $reasonForDifferentTesterNotAllowed = false;
            if ($this->authorizationService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS)) {
                $reasonForDifferentTesterNotAllowed = true;
            } else {
                $isDifferentTester = !$this->motTestSecurityService->isCurrentTesterAssignedToMotTest(
                    $draft->getMotTest()
                );
                if (!$isDifferentTester) {
                    $reasonForDifferentTesterNotAllowed = true;
                } else {
                    $draft->setReasonForDifferentTester(
                        $this->certificateChangeReasonRepository->getByCode($draftChange->getReasonForDifferentTester())
                    );
                }
            }
            if ($reasonForDifferentTesterNotAllowed) {
                throw new ForbiddenException("Reason for different tester is not allowed");
            }
        }
    }

    /**
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $draftChange
     *
     * @return ReplacementCertificateDraft
     */
    private function applyChangesSpecialFields(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $draftChange
    ) {
        if ($this->isTryingToModifySpecialField($draftChange)) {
            $this->authorizationService->assertGranted(
                PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS
            );

            if ($draftChange->isCustomMakeSet()) {
                $draft->setMake(null);
                $draft->setMakeName($draftChange->getCustomMake());
            }

            if ($draftChange->isCustomModelSet()) {
                $draft->setModelName($draftChange->getCustomModel());
                $draft->setModel(null);
            }

            if ($draftChange->isMakeSet() && !$draftChange->isCustomMakeSet()) {
                $draft->setMake($this->vehicleCatalog->getMake($draftChange->getMake()));
                $draft->setModelName(null);
                $draft->setMakeName(null);
                $draft->setModel(null);
            }
            if ($draftChange->isMakeSet() && $draftChange->isCustomModelSet()) {
                $draft->setMake($this->vehicleCatalog->getMake($draftChange->getMake()));
                $draft->setModelName($draftChange->getCustomModel());
                $draft->setMakeName(null);
                $draft->setModel(null);
            }
            if ($draftChange->isModelSet() && !$draftChange->isCustomModelSet()) {
                $draft->setModel(
                    $this->vehicleCatalog->getModel(
                        $draftChange->getMake(), $draftChange->getModel()
                    )
                );
            }
            if ($draftChange->isCountryOfRegistrationSet()) {
                $draft->setCountryOfRegistration(
                    $this->vehicleCatalog->getCountryOfRegistration(
                        $draftChange->getCountryOfRegistration()
                    )
                );
            }

            if ($draftChange->isVinSet()) {
                $draft->setVin($draftChange->getVin());

                if ($draft->getMotTest()->getVin() != $draftChange->getVin()) {
                    $draft->setIsVinRegistrationChanged(true);
                } else {
                    $draft->setIsVinRegistrationChanged(false);
                }
            }

            if ($draftChange->isVrmSet()) {
                $draft->setVrm($draftChange->getVrm());

                if ($draft->getMotTest()->getRegistration() != $draftChange->getVrm()) {
                    $draft->setIsVinRegistrationChanged(true);
                } else {
                    $draft->setIsVinRegistrationChanged(false);
                }
            }

            if ($draftChange->isExpiryDateSet()) {
                $draft->setExpiryDate(DateUtils::toDate($draftChange->getExpiryDate()));
            }
            if ($draftChange->isVtsSiteNumberSet()) {
                $draft->setVehicleTestingStation(
                    $this->vtsRepository->getBySiteNumber($draftChange->getVtsSiteNumber())
                );
            }
            if ($draftChange->isReasonForReplacementSet()) {
                $draft->setReplacementReason($draftChange->getReasonForReplacement());
            }
        }

        return $draft;
    }

    /**
     * @param ReplacementCertificateDraftChangeDTO $draftChange
     *
     * @return bool
     */
    private function isTryingToModifySpecialField(ReplacementCertificateDraftChangeDTO $draftChange)
    {
        return $draftChange->isMakeSet()
        || $draftChange->isModelSet()
        || $draftChange->isCountryOfRegistrationSet()
        || $draftChange->isVinSet()
        || $draftChange->isVrmSet()
        || $draftChange->isExpiryDateSet()
        || $draftChange->isVtsSiteNumberSet()
        || $draftChange->isReasonForReplacementSet()
        || $draftChange->isCustomMakeSet()
        || $draftChange->isCustomModelSet();
    }
}
