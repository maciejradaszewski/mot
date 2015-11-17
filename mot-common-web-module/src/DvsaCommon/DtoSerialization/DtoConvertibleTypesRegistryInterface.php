<?php

namespace DvsaCommon\DtoSerialization;

interface DtoConvertibleTypesRegistryInterface
{
    public function getConvertibleTypes();

    public function isConvertibleType($class);

    /**
     * @param $class
     * @return DtoConverterInterface
     */
    public function getConverter($class);
}
