<?php

namespace DvsaCommonTest\TestUtils;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class SampleDto
 *
 * @package DvsaCommonTest\TestUtils
 */
class SampleDto extends AbstractDataTransferObject
{
    private $name;
    private $arrayOfValues;
    private $enum;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $arrayOfValues
     */
    public function setArrayOfValues($arrayOfValues)
    {
        $this->arrayOfValues = $arrayOfValues;
    }

    /**
     * @return mixed
     */
    public function getArrayOfValues()
    {
        return $this->arrayOfValues;
    }

    /**
     * @param mixed $enum
     */
    public function setEnum($enum)
    {
        $this->enum = $enum;
    }

    /**
     * @return mixed
     */
    public function getEnum()
    {
        return $this->enum;
    }

}
