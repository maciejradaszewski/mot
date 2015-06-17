<?php

namespace DvsaCommonApi\Service\Mapper;

use DvsaCommon\Dto\Common\MotTestTypeDto;

class MotTestTypeMapper extends AbstractApiMapper
{
    public function toDto($entity)
    {
        $dto = new MotTestTypeDto();

        $dto
            ->setId($entity->getId())
            ->setCode($entity->getCode());

        return $dto;
    }
}
