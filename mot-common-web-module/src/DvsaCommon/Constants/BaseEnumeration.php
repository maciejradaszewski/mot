<?php


namespace DvsaCommon\Constants;

/**
 * Base class for all enumerations (constants container)
 *
 * Class BaseEnumeration
 * @package DvsaCommon\Constants
 */
class BaseEnumeration
{
    /**
     * Checks if an element is a member of a target enumeration class
     *
     * @param $element
     * @return bool
     */
    public static function isValid($element)
    {
        return in_array($element, self::getValues());
    }

    /**
     * returns all values of enumeration class
     *
     * @return string[]
     */
    public static function getValues()
    {
        return array_values((new \ReflectionClass(get_called_class()))->getConstants());
    }
}
