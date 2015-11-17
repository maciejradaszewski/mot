<?php

namespace DvsaCommon\DtoSerialization;

class DtoDeserializationException extends \Exception
{
    const PROPERTY_MISSING_IN_JSON = 1;
    const SCALAR_IN_DTO_ARRAY = 2;
    const NULL_IN_DTO_ARRAY = 3;
    const OBJECT_IN_DTO_ARRAY = 4;
    const NOT_ARRAY_OF_DTO_PROPERTIES = 5;

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public static function createJsonMissingPropertyException($propertyName, $dtoClass, $keys)
    {
        $template = "Property '%s' from '%s' DTO class was not found in JSON array. Existing keys: [%s]";

        $keysString = join(", ", $keys);

        $message = sprintf($template, $propertyName, $dtoClass, $keysString);

        return new DtoDeserializationException($message, self::PROPERTY_MISSING_IN_JSON);
    }

    public static function createScalarInsteadOfDtoArrayException($dtoClass, $scalarValue)
    {
        $template = "While deserializing array of DTOs of class '%s' a scalar value '%s' was encountered instead of an array of DTO's properties.";

        $message = sprintf($template, $dtoClass, $scalarValue);

        return new DtoDeserializationException($message, self::SCALAR_IN_DTO_ARRAY);
    }

    public static function createNullInsteadOfDtoArrayException($dtoClass)
    {
        $template = "While deserializing array of DTOs of class '%s' a null value was encountered instead of an array of DTO's properties.";

        $message = sprintf($template, $dtoClass);

        return new DtoDeserializationException($message, self::NULL_IN_DTO_ARRAY);
    }

    public static function createObjectInsteadOfDtoArrayException($dtoClass, $object)
    {
        $template = "While deserializing array of DTOs of class '%s' an object of class '%s' was encountered instead of an array of DTO's properties.";

        $objectClass = get_class($object);

        $message = sprintf($template, $dtoClass, $objectClass);

        return new DtoDeserializationException($message, self::OBJECT_IN_DTO_ARRAY);
    }

    public static function createNestedScalarInsteadOfDtoArrayException($dtoClass, $nestedDtoClass, $nestedPropertyName, $scalarValue)
    {
        $template = "Property '%s' if '%s' DTO was supossed to contain array of properties representing serialized '%s' DTO. "
            . "Instead scalar value was encountered: '%s'";

        $message = sprintf($template, $nestedPropertyName, $dtoClass, $nestedDtoClass, $scalarValue);

        return new DtoDeserializationException($message, self::NOT_ARRAY_OF_DTO_PROPERTIES);
    }
}
