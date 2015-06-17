<?php

namespace DvsaMotApi\Service\ReplacementCertificate;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Entity\CertificateReplacement;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Repository\CertificateReplacementRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\ReplacementCertificateDraftRepository;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;
use DvsaMotApi\Service\OtpService;
use DvsaMotApi\Service\CertificateCreationService;

/**
 * Class ReplacementCertificateService
 *
 * @package DvsaMotApi\Service\ReplacementCertificate
 */
class ReplacementCertificateService implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    /**
     * @var \DvsaEntities\Repository\ReplacementCertificateDraftRepository $repository
     */
    private $repository;
    /**
     * @var \DvsaAuthorisation\Service\AuthorisationServiceInterface $authService
     */
    private $authService;
    /**
     * @var ReplacementCertificateDraftUpdater $draftUpdater
     */
    private $draftUpdater;
    /**
     * @var ReplacementCertificateDraftCreator $draftCreator
     */
    private $draftCreator;
    /**
     * @var ReplacementCertificateUpdater $certificateUpdater
     */
    private $certificateUpdater;

    /**
     * @var CertificateReplacementRepository $certificateReplacementRepository
     */
    private $certificateReplacementRepository;
    /**
     * @var MotTestRepository $motTestRepository
     */
    private $motTestRepository;

    /**
     * @var OtpService $otpService
     */
    private $otpService;

    /**
     * @param ReplacementCertificateDraftRepository $repository
     * @param ReplacementCertificateDraftCreator    $draftCreator
     * @param ReplacementCertificateDraftUpdater    $draftUpdater
     * @param ReplacementCertificateUpdater         $certificateUpdater
     * @param CertificateReplacementRepository      $certificateReplacementRepository
     * @param AuthorisationServiceInterface      $authService
     * @param MotTestRepository                     $motTestRepository
     * @param OtpService                            $otpService
     */
    public function __construct(
        ReplacementCertificateDraftRepository $repository,
        ReplacementCertificateDraftCreator $draftCreator,
        ReplacementCertificateDraftUpdater $draftUpdater,
        ReplacementCertificateUpdater $certificateUpdater,
        CertificateReplacementRepository $certificateReplacementRepository,
        AuthorisationServiceInterface $authService,
        MotTestRepository $motTestRepository,
        OtpService $otpService,
        CertificateCreationService $certificateCreationService
    ) {
        $this->repository = $repository;
        $this->authService = $authService;
        $this->draftCreator = $draftCreator;
        $this->draftUpdater = $draftUpdater;
        $this->motTestRepository = $motTestRepository;
        $this->certificateUpdater = $certificateUpdater;
        $this->certificateReplacementRepository = $certificateReplacementRepository;
        $this->otpService = $otpService;
        $this->certificateCreationService = $certificateCreationService;
    }

    /**
     * @param $motTestNumber
     *
     * @return \DvsaEntities\Entity\ReplacementCertificateDraft
     */
    public function createDraft($motTestNumber, $replacementReason = '')
    {
        $this->authService->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);
        $motTest = $this->motTestRepository->getMotTestByNumber($motTestNumber);
        $draft = $this->draftCreator->create($motTest, $replacementReason);
        $this->repository->save($draft);
        return $draft;
    }

    /**
     * @param                                      $draftId
     * @param ReplacementCertificateDraftChangeDTO $changeDTO
     */
    public function updateDraft($draftId, ReplacementCertificateDraftChangeDTO $changeDTO)
    {
        $this->authService->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);
        $draft = $this->repository->get($draftId);
        $this->draftUpdater->updateDraft($draft, $changeDTO);
    }

    /**
     * @param string $motTestNumber
     * @param string $replacementReason
     * @param ReplacementCertificateDraftChangeDTO $changeDto
     *
     * @return \DvsaEntities\Entity\ReplacementCertificateDraft
     * @throws \DvsaCommonApi\Service\Exception\ForbiddenException
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function createAndUpdateDraft(
        $motTestNumber,
        $replacementReason,
        ReplacementCertificateDraftChangeDTO $changeDto
    ) {
        $this->authService->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);
        $motTest = $this->motTestRepository->getMotTestByNumber($motTestNumber);
        $draft = $this->draftCreator->create($motTest, $replacementReason);
        $this->draftUpdater->updateDraft($draft, $changeDto);
        $this->repository->save($draft);
        return $draft;
    }

    /**
     * @param $draftId
     *
     * @return \DvsaEntities\Entity\ReplacementCertificateDraft
     */
    public function getDraft($draftId)
    {
        $this->authService->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);

        return $this->repository->get($draftId);
    }

    /**
     * @param integer $draftId
     * @param array   $data
     *
     * @return MotTest
     */
    public function applyDraft($draftId, $data)
    {
        $this->authService->assertGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT);

        if (!$this->authService->isGranted(PermissionInSystem::MOT_TEST_WITHOUT_OTP)) {
            $token = ArrayUtils::tryGet($data, 'oneTimePassword');
            $this->otpService->authenticate($token);
        }

        $motTest = $this->inTransaction(
            function () use ($draftId) {
                $draft = $this->repository->get($draftId);
                $motTest = $this->certificateUpdater->update($draft);

                $certificateReplacement = (new CertificateReplacement())
                    ->setMotTest($draft->getMotTest())
                    ->setMotTestVersion($draft->getMotTestVersion())
                    ->setReasonForDifferentTester($draft->getReasonForDifferentTester())
                    ->setReplacementReason($draft->getReplacementReason())
                    ->setIsVinRegistrationChanged($draft->getIsVinRegistrationChanged());

                $this->certificateReplacementRepository->save($certificateReplacement);
                $this->repository->remove($draft);
                return $motTest;
            }
        );

        return $motTest;
    }

    /**
     * @param string $motTestNumber
     * @param string $userId
     */
    public function createCertificate($motTestNumber, $userId)
    {
        return $this->certificateCreationService->createFromMotTestNumber($motTestNumber, $userId);
    }
}
