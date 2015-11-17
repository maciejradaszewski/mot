<?php

namespace DvsaCommon\DtoSerialization;

class CallbackDtoConverter implements DtoConverterInterface
{
    private $jsonToObjectCallback;
    private $objectToJsonCallback;

    public function __construct($jsonToObject, $objectToJson)
    {
        $this->jsonToObjectCallback = $jsonToObject;
        $this->objectToJsonCallback = $objectToJson;
    }

    public function jsonToObject($json)
    {
        $callback = $this->jsonToObjectCallback;

        return $callback($json);
    }

    public function objectToJson($object)
    {
        $callback = $this->objectToJsonCallback;

        return $callback($object);
    }
}
