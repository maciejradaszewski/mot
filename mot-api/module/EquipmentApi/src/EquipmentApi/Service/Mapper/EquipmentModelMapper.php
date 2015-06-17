<?php

namespace EquipmentApi\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Equipment\EquipmentModelDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaEntities\Entity\EquipmentModel;
use DvsaEntities\Entity\VehicleClass;

/**
 * Class EquipmentMapper
 */
class EquipmentModelMapper
{
    public function toDto(EquipmentModel $equipmentModel)
    {
        $dto = new EquipmentModelDto();
        $dto->setName($equipmentModel->getName());
        $dto->setCode($equipmentModel->getCode());
        $dto->setMakeName($equipmentModel->getMake()->getName());
        $dto->setTypeName($equipmentModel->getType()->getName());
        $dto->setStatus($equipmentModel->getStatus()->getCode());

        $dto->setEquipmentIdentificationNumber($equipmentModel->getEquipmentIdentificationNumber());
        $dto->setCertificationDate(DateTimeApiFormat::date($equipmentModel->getCertificationDate()));
        $dto->setSoftwareVersion($equipmentModel->getSoftwareVersion());

        $vehicleClassDtos = ArrayUtils::map(
            $equipmentModel->getVehiclesClasses(),
            function (VehicleClass $vehicleClass) {
                $dto = new VehicleClassDto();
                $dto->setId($vehicleClass->getId());
                $dto->setName($vehicleClass->getName());

                return $dto;
            }
        );

        $dto->setVehicleClasses($vehicleClassDtos);

        return $dto;
    }

    /**
     * @param $equipmentModels EquipmentModel[]
     *
     * @return EquipmentModelDto[]
     */
    public function manyToDto($equipmentModels)
    {
        return ArrayUtils::map(
            $equipmentModels, function (EquipmentModel $model) {
                return $this->toDto($model);
            }
        );
    }
}
