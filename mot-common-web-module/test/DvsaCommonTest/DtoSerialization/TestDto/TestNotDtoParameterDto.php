<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\DtoReflector;
use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestNotDtoParameterDto implements ReflectiveDtoInterface
{
    private $notDtoParameter;

    public function getNotDtoParameter()
    {
        return $this->notDtoParameter;
    }

    // DtoReflector is not a DTO, which makes it invalid as a parameter in this DTO class.
    public function setNotDtoParameter(DtoReflector $notDtoParameter)
    {
        $this->notDtoParameter = $notDtoParameter;
    }
}
