<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestTooManyParamsDto implements ReflectiveDtoInterface
{
    private $tooManyParamsProperty;

    public function getTooManyParamsProperty()
    {
        return $this->tooManyParamsProperty;
    }

    public function setTooManyParamsProperty(TestNestedDto $tooManyParamsProperty, $excessiveParam)
    {
        $this->tooManyParamsProperty = $tooManyParamsProperty;
    }
}
