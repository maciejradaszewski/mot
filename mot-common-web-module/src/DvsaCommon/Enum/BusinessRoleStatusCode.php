<?php

namespace DvsaCommon\Enum;

/**
 * Enum class generated from the 'business_role_status' table
 *
 * DO NOT EDIT! -- THIS CLASS IS GENERATED BY mot-common-web-module/generate_enums.php
 * @codeCoverageIgnore
 */
class BusinessRoleStatusCode
{
    const ACTIVE = 'AC';
    const INACTIVE = 'IN';
    const DISQUALIFIED = 'DI';
    const PENDING = 'PEND';
    const ACCEPTED = 'ACC';
    const REJECTED = 'RJ';
    const REMOVED = 'RE';

    /**
     * @return array of values for the type BusinessRoleStatusCode
     */
    public static function getAll()
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::DISQUALIFIED,
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED,
            self::REMOVED,
        ];
    }

    /**
     * @param mixed $key a candidate BusinessRoleStatusCode value
     *
     * @return true if $key is in the list of known values for the type
     */
    public static function exists($key)
    {
        return in_array($key, self::getAll(), true);
    }
}
