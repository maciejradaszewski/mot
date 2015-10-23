<?php

namespace DvsaCommon\Utility;

class TypeCheck
{
    /**
     * Return true is the arg is a positive int; false otherwise.
     *
     * @param mixed $number
     *
     * @return bool
     */
    public static function isPositiveInteger($number)
    {
        if (!self::isInteger($number)) {
            return false;
        }

        if ((int)$number >= 0) {
            return true;
        }

        return false;
    }


    public static function assertIsPositiveInteger($number)
    {
        if (!self::isPositiveInteger($number)) {
            throw new \InvalidArgumentException("Positive integer expected" );
        }
    }

    /**
     * Throws \RuntimeException when passed object (1st arg) is not instance of given class (2nd arg)
     *
     * @param $object
     * @param string $class
     *
     * @throws \RuntimeException
     */
    public static function assertInstance($object, $class)
    {
        if (false === ($object instanceof $class)) {
            throw new \RuntimeException('Expected ' . $class . ' object. ' . get_class($object) . ' given.');
        }
    }

    /**
     * Return true is the arg represents integer (might be string, but with integer value)
     *
     * @param mixed $number
     *
     * @return bool
     */
    public static function isInteger($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if (is_string($number) && !preg_match("/^-?[0-9]*$/", $number)) {
            return false;
        }

        if ((string)(int)$number != $number) {
            return false;
        }

        return true;
    }

    /**
     * Return true if the arg is a string; false otherwise.
     *
     * @param mixed $string
     *
     * @return bool
     */
    public static function isAlphaNumeric($string)
    {
        if (preg_match('/[^a-zA-Z0-9]+/', $string)) {
            return false;
        }
        if (strlen($string) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Verifies type of passed argument. Throws \InvalidArgumentException if not an array
     *
     * Please use this method to make sure an array is passed to method/function.
     * Do not rely on keyword 'array' in function as it cause an error instead of exception.
     * Exception is much better because it has stack trace and more useful information.
     *
     * @param mixed $data
     */
    public static function assertArray($data)
    {
        if (false === is_array($data)) {
            throw new \InvalidArgumentException('Expected array, ' . gettype($data) . ' given.');
        }
    }

    public static function assertCollectionOfClass($data, $className)
    {
        if (ArrayUtils::anyMatch(
            $data,
            function ($element) use ($className) {
                return get_class($element) != $className;
            }
        )) {
            throw new \InvalidArgumentException("Expected collection of '" . $className . "'.");
        }
    }

    public static function assertCollectionOfScalarValues($collection)
    {
        foreach ($collection as $element) {
            if ($element === null) {
                throw new \InvalidArgumentException("Expected collection of scalar values. Got null");
            }

            if (is_object($element)) {
                throw new \InvalidArgumentException("Expected collection of scalar values."
                    . "Got object of class '" . get_class($element) . "'"
                );
            }

            if (is_array($element)) {
                throw new \InvalidArgumentException("Expected collection of scalar values. Got array." );
            }
        }
    }
}
