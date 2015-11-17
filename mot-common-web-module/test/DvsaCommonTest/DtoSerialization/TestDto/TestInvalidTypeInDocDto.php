<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestInvalidTypeInDocDto implements ReflectiveDtoInterface
{
    private $invalidTypeInDocParameter;

    public function getInvalidTypeInDocParameter()
    {
        return $this->invalidTypeInDocParameter;
    }

    /**
     * decimal is an unknown type
     *
     * @param decimal $invalidTypeInDocParameter
     */
    public function setInvalidTypeInDocParameter($invalidTypeInDocParameter)
    {
        $this->invalidTypeInDocParameter = $invalidTypeInDocParameter;
    }
}
