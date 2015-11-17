<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class SampleDto implements ReflectiveDtoInterface
{
    private $name;

    private $names;

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param string[] $names
     */
    public function setNames(array $names)
    {
        $this->names = $names;
    }
}
