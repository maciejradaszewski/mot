<?php

namespace DvsaCommon\DtoSerialization;

class ScalarTypesList
{
    private static $types = [
        'int',
        'integer',
        'string',
        'float',
        'bool',
        'boolean',
        'double',
    ];

    public static function isScalar($type)
    {
        $type = strtolower($type);
        return in_array($type, self::$types);
    }
}
