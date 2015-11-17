<?php

namespace DvsaCommon\DtoSerialization;

class DtoPropertyReflection
{
    private $name;
    private $retrieveAccessor;
    private $class;
    private $isArray;
    private $type;

    public function __construct($name, DtoPropertyType $type, $retrieveAccessor)
    {
        $this->name = $name;
        $this->retrieveAccessor = $retrieveAccessor;
        $this->class = $type->getClass();
        $this->isArray = $type->isArray();
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getClass()
    {
        return $this->type->getClass();
    }

    public function getRetrieveAccessor()
    {
        return $this->retrieveAccessor;
    }

    public function getSetAccessor()
    {
        return 'set' . $this->name;
    }

    public function isArray()
    {
        return $this->type->isArray();
    }

    public function isDto()
    {
        return $this->type->isDto();
    }

    public function isScalarType()
    {
        return $this->type->isScalarType();
    }

    public function isConvertible()
    {
        return $this->type->isConvertible();
    }
}
