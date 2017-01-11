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

    const EXPIRY_DATE_FORMAT = 'Y-m-d';

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

        $this->applyChangesSpecialFields($draft, $draftChange);

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
     */
    private function applyChangesSpecialFields(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $draftChange
    ) {
        if ($this->isTryingToModifySpecialField($draftChange)) {
            $this->authorizationService->assertGranted(
                PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS
            );

            $this->performMismatchChanges($draft, $draftChange);
            $this->performNonMismatchChanges($draft, $draftChange);
        }
    }

    /**
     * Perform changes from the ReplacementCertificateDraftChangeDTO which will
     * cause a mismatch or pass to be sent to DVLA
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updates
     * @return ReplacementCertificateDraft
     * @throws \DvsaCommon\Date\Exception\IncorrectDateFormatException
     */
    private function performMismatchChanges(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updates
    ) {
        $this->setMismatchAndPassFlags($draft, $updates);

        $vrmChanged = $this->vrmHasChanged($draft, $updates);
        $vinChanged = $this->vinHasChanged($draft, $updates);
        $dateChanged = $this->dateHasChanged($draft, $updates);

        if ($vrmChanged || $vinChanged || $dateChanged) {
            $draft->setIsVinVrmExpiryChanged(true);
        } elseif (!$draft->isVinVrmExpiryChangedIsTrue()) {
            $draft->setIsVinVrmExpiryChanged(false);
        }

        if ($vinChanged) {
            $draft->setVin($updates->getVin());
        }

        if ($vrmChanged) {
            $draft->setVrm($updates->getVrm());
        }

        if ($dateChanged) {
            $draft->setExpiryDate(DateUtils::toDate($updates->getExpiryDate()));
        }
    }

    /**
     * Set the flags to indicate whether the record should be sent to DVLA in the Batch Export mismatch and/or pass file
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updates
     * @return ReplacementCertificateDraft
     */
    private function setMismatchAndPassFlags(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updates
    ) {
        // refactor into comparison functions in ReplacementCertificateDraftChangeDto
        $vrmChanged = $this->vrmHasChanged($draft, $updates);
        $vinChanged = $this->vinHasChanged($draft, $updates);
        $dateChanged = $this->dateHasChanged($draft, $updates);

        // criteria outlined at:
        // https://jira.i-env.net/browse/VM-12328?focusedCommentId=61392&page=com.atlassian.jira.plugin.system.issuetabpanels:comment-tabpanel#comment-61392
        if ($this->changedByDVLA()) {
            // makes the assumption that batch import will never be able to
            //update expiry date of replacement certificate
            if ($vrmChanged || $vinChanged || $dateChanged) {
                $draft->setIncludeInPassFile(true);
            } elseif (!$draft->includeInPassFileIsTrue()) {
                $draft->setIncludeInPassFile(false);
            }

            if (!$draft->includeInMismatchFileIsTrue()) {
                $draft->setIncludeInMismatchFile(false);
            }
        } else {
            if ($vrmChanged) {
                $draft->setIncludeInPassFile(true);
                $draft->setIncludeInMismatchFile(true);
            }
            if ($vinChanged) {
                $draft->setIncludeInMismatchFile(true);
            }
            if ($dateChanged) {
                $draft->setIncludeInPassFile(true);
            }

            if (!$draft->includeInMismatchFileIsTrue()) {
                $draft->setIncludeInMismatchFile(false);
            }
            if (!$draft->includeInPassFileIsTrue()) {
                $draft->setIncludeInPassFile(false);
            }
        }
    }

    /**
     * Perform all changes to the vehicle which will not trigger a mismatch to be sent to DVLA
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updates
     * @return ReplacementCertificateDraft
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    private function performNonMismatchChanges(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updates
    ) {

        if (!$updates->isDvlaImportProcess()) {
            $this->performMakeModelChanges($draft, $updates);
        }

        if ($updates->isCountryOfRegistrationSet()) {
            $draft->setCountryOfRegistration(
                $this->vehicleCatalog->getCountryOfRegistration(
                    $updates->getCountryOfRegistration()
                )
            );
        }

        if ($updates->isVtsSiteNumberSet()) {
            $draft->setVehicleTestingStation(
                $this->vtsRepository->getBySiteNumber($updates->getVtsSiteNumber())
            );
        }

        if ($updates->isReasonForReplacementSet()) {
            $draft->setReplacementReason($updates->getReasonForReplacement());
        }
    }

    /**
     * Perform all changes in the ReplacementCertificateDraftChangeDTO to do with make and model
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updates
     * @return ReplacementCertificateDraft
     */
    private function performMakeModelChanges(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updates
    ) {
        if ($updates->isCustomMakeSet()) {
            $draft->setMake(null);
            $draft->setMakeName($updates->getCustomMake());
        }

        if ($updates->isCustomModelSet()) {
            $draft->setModelName($updates->getCustomModel());
            $draft->setModel(null);
        }

        if ($updates->isMakeSet() && !$updates->isCustomMakeSet()) {
            $draft->setMake($this->vehicleCatalog->findMakeById($updates->getMake()));
            $draft->setModelName(null);
            $draft->setMakeName(null);
            $draft->setModel(null);
        }
        if ($updates->isMakeSet() && $updates->isCustomModelSet()) {
            $draft->setMake($this->vehicleCatalog->findMakeById($updates->getMake()));
            $draft->setModelName($updates->getCustomModel());
            $draft->setMakeName(null);
            $draft->setModel(null);
        }

        if ($updates->isModelSet() && !$updates->isCustomModelSet()) {
            $make = $this->vehicleCatalog->findMakeById($updates->getMake());
            $draft->setModel($this->vehicleCatalog->getModel($make->getId(), $updates->getModel()));
        }
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

    /**
     * Return if changes are being made by a DVLA user
     *
     * @return bool
     */
    private function changedByDVLA()
    {
        return $this->authorizationService->isGranted(
            PermissionInSystem::CERTIFICATE_REPLACEMENT_DVLA_CHANGE
        );
    }

    /**
     * Returns whether the vrm in the draft change is different to that of the original
     * ReplacementCertificate
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updated
     * @return bool
     */
    private function vrmHasChanged(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updated
    ) {
        return $updated->isVrmSet() && strcmp($draft->getVrm(), $updated->getVrm()) != 0;
    }

    /**
     * Returns whether the vin in the draft change is different to that of the original
     * ReplacementCertificate
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updated
     * @return bool
     */
    private function vinHasChanged(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updated
    ) {
        return $updated->isVinSet() && strcmp($draft->getVin(), $updated->getVin()) != 0;
    }

    /**
     * Returns whether the expiry date in the draft change is different to that of the original
     * ReplacementCertificate
     *
     * @param ReplacementCertificateDraft          $draft
     * @param ReplacementCertificateDraftChangeDTO $updated
     * @return bool
     */
    private function dateHasChanged(
        ReplacementCertificateDraft $draft,
        ReplacementCertificateDraftChangeDTO $updated
    ) {
        $oldDate = !empty($draft->getExpiryDate()) ? $draft->getExpiryDate()->format(self::EXPIRY_DATE_FORMAT) : '';

        return $updated->isExpiryDateSet() &&
            strcmp($oldDate, $updated->getExpiryDate()) != 0;
    }
}
