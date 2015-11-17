<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestMixedTypeDto implements ReflectiveDtoInterface
{
    private $mixedProperty;

    public function getMixedProperty()
    {
        return $this->mixedProperty;
    }

    /**
     * @param Mixed $mixedProperty
     */
    public function setMixedProperty($mixedProperty)
    {
        $this->mixedProperty = $mixedProperty;
    }
}
