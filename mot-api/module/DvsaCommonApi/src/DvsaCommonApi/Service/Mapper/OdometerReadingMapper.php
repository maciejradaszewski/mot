<?php

namespace DvsaCommonApi\Service\Mapper;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;
use DvsaCommon\Dto\Common\OdometerReadingDTO;

class OdometerReadingMapper extends AbstractApiMapper
{
    public function toDto($entity)
    {
        $dto = new OdometerReadingDTO();

        $dto
            ->setResultType($entity->getResultType())
            ->setUnit($entity->getUnit())
            ->setValue($entity->getValue());

        return $dto;
    }

    public function manyToDtoFromArray($datas)
    {
        TypeCheck::assertArray($datas);

        $result = [];

        foreach ($datas as $data) {
            $result[] = $this->toDtoFromArray($data);
        }

        return $result;
    }

    public function toDtoFromArray($data)
    {
        $dto = new OdometerReadingDTO();

        $dto
            ->setResultType(ArrayUtils::tryGet($data, 'resultType', null))
            ->setUnit(ArrayUtils::tryGet($data, 'unit', null))
            ->setValue(ArrayUtils::tryGet($data, 'value', null))
            ->setIssuedDate(ArrayUtils::tryGet($data, 'issuedDate', null));

        return $dto;
    }
}
