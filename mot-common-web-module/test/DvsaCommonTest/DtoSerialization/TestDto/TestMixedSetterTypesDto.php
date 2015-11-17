<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestMixedSetterTypesDto implements ReflectiveDtoInterface
{
    private $mixedProperty;

    public function getMixedProperty()
    {
        return $this->mixedProperty;
    }

    /**
     * @param TestValidDto $mixedProperty (the mismatch here is on purpose)
     */
    public function setMixedProperty(TestNestedDto $mixedProperty)
    {
        $this->mixedProperty = $mixedProperty;
    }
}
