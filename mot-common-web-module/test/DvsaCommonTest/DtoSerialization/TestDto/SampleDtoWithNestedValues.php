<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class SampleDtoWithNestedValues implements ReflectiveDtoInterface
{
    /** @var \DvsaCommonTest\DtoSerialization\TestDto\SampleDto */
    private $nestedDto;

    /** @var \DvsaCommonTest\DtoSerialization\TestDto\SampleDto[] */
    private $nestedDtoList;

    /**
     * @param \DvsaCommonTest\DtoSerialization\TestDto\SampleDto $nestedDto
     */
    public function setNestedDto($nestedDto)
    {
        $this->nestedDto = $nestedDto;
    }

    public function getNestedDto()
    {
        return $this->nestedDto;
    }

    /**
     * @param \DvsaCommonTest\DtoSerialization\TestDto\SampleDto[] $nestedListOfDtos
     */
    public function setNestedDtoList($nestedListOfDtos)
    {
        $this->nestedDtoList = $nestedListOfDtos;
    }

    public function getNestedDtoList()
    {
        return $this->nestedDtoList;
    }
}
