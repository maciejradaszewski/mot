<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestArrayInConstraintSingleInDocDto implements ReflectiveDtoInterface
{
    private $invalidParam;

    public function getInvalidParam()
    {
        return $this->invalidParam;
    }

    /**
     * This is invalid because signature expects an array.
     * While doc states an dto object
     *
     * @param \DvsaCommonTest\DtoSerialization\TestDto\TestNestedDto $invalidParam
     */
    public function setInvalidParam(array $invalidParam)
    {
        $this->invalidParam = $invalidParam;
    }
}
