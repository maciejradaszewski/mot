<?php

namespace DvsaCommonTest\TestUtils;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class SampleDtoWithNestedValues
 *
 * @package DvsaCommonTest\TestUtils
 */
class SampleDtoWithNestedValues extends AbstractDataTransferObject
{
    /** @var SampleDto */
    private $nestedDto;

    /** @var SampleDto[] */
    private $nestedDtoList;

    /**
     * @param \DvsaCommonTest\TestUtils\SampleDto $nestedDto
     */
    public function setNestedDto($nestedDto)
    {
        $this->nestedDto = $nestedDto;
    }

    /**
     * @return \DvsaCommonTest\TestUtils\SampleDto
     */
    public function getNestedDto()
    {
        return $this->nestedDto;
    }

    /**
     * @param \DvsaCommonTest\TestUtils\SampleDto[] $nestedListOfDtos
     */
    public function setNestedDtoList($nestedListOfDtos)
    {
        $this->nestedDtoList = $nestedListOfDtos;
    }

    /**
     * @return \DvsaCommonTest\TestUtils\SampleDto[]
     */
    public function getNestedDtoList()
    {
        return $this->nestedDtoList;
    }

}
