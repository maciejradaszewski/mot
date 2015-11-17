<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestNotDtoParameterInDocDto implements ReflectiveDtoInterface
{
    private $notADtoParameter;

    public function getNotADtoParameter()
    {
        return $this->notADtoParameter;
    }

    /**
     * Guid is not a DTO and cannot be nested in other DTOs
     *
     * @param \DvsaCommon\Guid\Guid $notADtoParameter
     */
    public function setNotADtoParameter($notADtoParameter)
    {
        $this->notADtoParameter = $notADtoParameter;
    }
}
