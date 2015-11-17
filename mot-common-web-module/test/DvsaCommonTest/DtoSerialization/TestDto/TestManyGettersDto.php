<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestManyGettersDto implements ReflectiveDtoInterface
{
    private $propertyWithManyGetters;

    public function getPropertyWithManyGetters()
    {
        return $this->propertyWithManyGetters;
    }

    public function hasPropertyWithManyGetters()
    {
        return $this->propertyWithManyGetters;
    }

    public function setPropertyWithManyGetters($propertyWithManyGetters)
    {
        $this->propertyWithManyGetters = $propertyWithManyGetters;
    }
}
