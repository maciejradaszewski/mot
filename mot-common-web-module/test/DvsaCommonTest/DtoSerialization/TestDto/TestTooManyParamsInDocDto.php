<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class TestTooManyParamsInDocDto implements ReflectiveDtoInterface
{
    private $manyParamsInDoc;

    public function getManyParamsInDoc()
    {
        return $this->manyParamsInDoc;
    }

    /**
     * This comment is invalid on purpose
     *
     * @param int $manyParamsInDoc2
     * @param int $manyParamsInDoc
     */
    public function setManyParamsInDoc($manyParamsInDoc)
    {
        $this->manyParamsInDoc = $manyParamsInDoc;
    }
}
