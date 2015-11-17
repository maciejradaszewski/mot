<?php

namespace DvsaCommon\DtoSerialization;

class DtoObjectProperty
{
    private $method;
    private $attribute;
    private $object;

    public function __construct($method, $object)
    {
        $this->method = $method;
        $this->object = $object;
        $this->attribute = $this->attributeNameFromMethod($method, $object);
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getObject()
    {
        return $this->object;
    }

    private function attributeNameFromMethod($method, $object)
    {
        $attribute = $method;
        if (preg_match('/^get/', $method)) {
            $attribute = substr($method, 3);
            if (!property_exists($object, $attribute)) {
                $attribute = lcfirst($attribute);
            }
        }

        return $attribute;
    }
}
