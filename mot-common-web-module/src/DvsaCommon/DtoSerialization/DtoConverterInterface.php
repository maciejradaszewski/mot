<?php

namespace DvsaCommon\DtoSerialization;

interface DtoConverterInterface
{
    public function jsonToObject($json);

    public function objectToJson($object);
}
