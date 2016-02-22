<?php

namespace IntegrationApi\DvlaVehicle\Service;

use DvsaCommon\Enum\MotTestStatusName;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\MotTestRepository;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Repository\VehicleRepository;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;

/**
 * Class DvlaVehicleUpdatedService
 */
class DvlaVehicleUpdatedService implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    const CHERISHED_TRANSFER_REASON = 'DVLA Cherished Transfer';

    /**
     * @var MotTestRepository
     */
    private $motTestRepository;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var ReplacementCertificateService
     */
    private $replacementCertificateService;

    /**
     * @param MotTestRepository $motTestRepository
     * @param ReplacementCertificateService $replacementCertificateService
     */
    public function __construct(
        MotTestRepository $motTestRepository,
        VehicleRepository $vehicleRepository,
        ReplacementCertificateService $replacementCertificateService
    ) {
        $this->motTestRepository = $motTestRepository;
        $this->replacementCertificateService = $replacementCertificateService;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Get the latest passed MOT test number for a vehicle ID
     * @param int $vehicleId
     * @param int $userId
     * @return bool
     */
    public function createReplacementCertificate($vehicleId, $userId)
    {
        $latestMotTestNumber = $this->motTestRepository->getLatestMotTestIdByVehicleId($vehicleId);
        /** @var Vehicle $vehicle */
        $vehicle = $this->vehicleRepository->find($vehicleId);
        $changeDto = new ReplacementCertificateDraftChangeDTO();
        $changeDto->setVrm($vehicle->getRegistration());
        $changeDto->setVin($vehicle->getVin());

        return $this->inTransaction(
            function () use ($latestMotTestNumber, $userId, $changeDto) {
                $reason = self::CHERISHED_TRANSFER_REASON;
                $draftId = $this->replacementCertificateService->createAndUpdateDraft(
                    $latestMotTestNumber, $reason, $changeDto
                )->getId();
                $this->replacementCertificateService->applyDraft($draftId, []);
                $this->replacementCertificateService->createCertificate($latestMotTestNumber, $userId);
            }
        );
    }
}
