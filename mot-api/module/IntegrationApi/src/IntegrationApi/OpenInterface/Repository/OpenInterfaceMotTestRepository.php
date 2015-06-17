<?php

namespace IntegrationApi\OpenInterface\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;

/**
 * Class OpenInterfaceMotTestRepository
 */
class OpenInterfaceMotTestRepository
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns longest valid MOT Test being issued before or on provided day.
     *
     * @param string $vehicleId
     * @param string $before
     * @param string $status
     *
     * @return MotTest
     */
    public function findLatestMotTest($vehicleId, $before, $status)
    {
        $motTest = $this->entityManager
            ->getRepository(MotTest::class)
            ->getLatestMotTestByVehicleIdAndResult(
                $vehicleId,
                $status,
                $before
            );
        return $motTest;
    }

    /**
     * Returns longest valid MOT Test being issued before or on provided day.
     *
     * @param string $vrm
     * @param string $before
     * @param string $status
     * @param array $excludeCodes
     *
     * @return MotTest
     */
    public function findLatestMotTestForVrm($vrm, $before, $status, $excludeCodes = [
        MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
        MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST
    ]) {
        return $this->entityManager
            ->getRepository(MotTest::class)
            ->findLatestMotTestByVrmAndResult(
                $vrm,
                $status,
                $before,
                $excludeCodes
            );
    }

    /**
     * @return Vehicle
     */
    public function findVehicleByVrm($vrm)
    {
        $vehicle = $this->entityManager->getRepository(Vehicle::class)->findOneByRegistration($vrm);

        return $vehicle;
    }
}
