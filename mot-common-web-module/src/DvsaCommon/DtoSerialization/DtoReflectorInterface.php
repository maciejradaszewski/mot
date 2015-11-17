<?php

namespace DvsaCommon\DtoSerialization;

interface DtoReflectorInterface
{
    /**
     * @param $dtoClass
     *
     * @return DtoClassReflection
     */
    public function reflect($dtoClass);
}
