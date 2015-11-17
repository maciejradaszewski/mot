<?php

namespace DvsaCommon\DtoSerialization;

class DtoPropertyType
{
    const SCALAR_TYPE = 'SCALAR';
    const DTO_TYPE = 'DTO';
    const CONVERTIBLE_TYPE = 'CONVERTIBLE';

    private $class;
    private $isArray;
    private $type;

    public function __construct($name, $type, $isArray)
    {
        $this->class = $name;
        $this->isArray = $isArray;
        $this->type = $type;

        if (!in_array($type, [self::SCALAR_TYPE, self::DTO_TYPE, self:: CONVERTIBLE_TYPE])) {
            throw new \InvalidArgumentException();
        }
    }

    public function getClass()
    {
        return $this->class;
    }

    public function isArray()
    {
        return $this->isArray;
    }

    public function isConvertible()
    {
        return $this->type == self::CONVERTIBLE_TYPE;
    }

    public function isDto()
    {
        return $this->type == self::DTO_TYPE;
    }

    public function isScalarType()
    {
        return $this->type == self::SCALAR_TYPE;
    }
}
