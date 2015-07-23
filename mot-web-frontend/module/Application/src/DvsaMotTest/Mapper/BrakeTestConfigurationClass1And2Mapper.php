<?php

namespace DvsaMotTest\Mapper;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationClass1And2Dto;
use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationDtoInterface;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;
use DvsaEntities\Repository\BrakeTestTypeRepository;

/**
 * Maps form data to BrakeTestConfigurationClass1And2Dto
 */
class BrakeTestConfigurationClass1And2Mapper implements BrakeTestConfigurationMapperInterface
{
    /**
     * @param array $data
     *
     * @return BrakeTestConfigurationDtoInterface
     */
    public function mapToDto($data)
    {
        TypeCheck::assertArray($data);

        $dto = new BrakeTestConfigurationClass1And2Dto();

        $riderWeight = ArrayUtils::tryGet($data, 'riderWeight');

        // value sometimes gets set to a blank sting in the session container
        if (empty($riderWeight)) {
            $riderWeight = null;
        }

        $dto->setBrakeTestType(ArrayUtils::tryGet($data, 'brakeTestType'));
        $dto->setVehicleWeightFront(ArrayUtils::tryGet($data, 'vehicleWeightFront'));
        $dto->setVehicleWeightRear(ArrayUtils::tryGet($data, 'vehicleWeightRear'));
        $dto->setRiderWeight($riderWeight);
        $dto->setSidecarWeight(ArrayUtils::tryGet($data, 'sidecarWeight'));
        $dto->setIsSidecarAttached(ArrayUtils::tryGet($data, 'isSidecarAttached') === '1');

        return $dto;
    }

    /**
     * @param MotTestDto $motTest
     *
     * @return BrakeTestConfigurationDtoInterface
     */
    public function mapToDefaultDto(MotTestDto $motTest)
    {
        $dto = new BrakeTestConfigurationClass1And2Dto();

        $dto->setBrakeTestType(BrakeTestTypeCode::ROLLER);
        $dto->setVehicleWeightFront('');
        $dto->setVehicleWeightRear('');
        $dto->setRiderWeight('');
        $dto->setSidecarWeight('');
        $dto->setIsSidecarAttached(false);

        if ($motTest->getVehicleTestingStation() !== null) {
            $dto->setBrakeTestType($motTest->getVehicleTestingStation()['defaultBrakeTestClass1And2']);
        }

        return $dto;
    }
}
