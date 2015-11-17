<?php

namespace DvsaCommon\DtoSerialization;

class DtoSerializationException extends \Exception
{
    const CANNOT_SERIALIZE_NON_DTOS = 1;
    const MIXED_TYPES_IN_ARRAY = 2;
    const CANNOT_SERIALIZE_SCALAR_VALUES = 3;
    const NESTED_DTO_ARRAY_HAS_NULL = 4;
    const CANNOT_SERIALIZE_NULL = 5;
    const NESTED_DTO_ARRAY_HAS_WRONG_OBJECT = 6;
    const NESTED_DTO_ARRAY_HAS_SCALAR = 7;
    const CANNOT_SERIALIZE_ARRAY_WITH_SCALAR = 8;
    const CANNOT_SERIALIZE_ARRAY_WITH_NULL = 9;

    public function __construct($message = "", $code = null)
    {
        parent::__construct($message, $code);
    }

    public static function createCannotSerializeNonDtosException($nonDtoObject)
    {
        $template = "Cannot serialize object of class: '%s'. "
            . "Please use only objects implementing '%s'";

        $nonDtoClass = get_class($nonDtoObject);

        $dtoInterface = ReflectiveDtoInterface::class;

        $message = sprintf($template, $nonDtoClass, $dtoInterface);

        return new DtoSerializationException($message, self::CANNOT_SERIALIZE_NON_DTOS);
    }

    public static function createMixedTypesInArrayException($class1, $class2)
    {
        $template = "Cannot serialize an array with elements of different types. "
            . "Two different type encountered: '%s' and '%s'.";

        $message = sprintf($template, $class1, $class2);

        return new DtoSerializationException($message, self::MIXED_TYPES_IN_ARRAY);
    }

    public static function createCannotSerializeScalarValuesException($scalarValue)
    {
        $template = "Cannot serialize a scalar value: '%s'";

        $message = sprintf($template, $scalarValue);

        return new DtoSerializationException($message, self::CANNOT_SERIALIZE_SCALAR_VALUES);
    }

    public static function createNestedArrayOfDtosHadNullException($expected)
    {
        $template = "Expected array of DTOs of class '%s' had null";

        $message = sprintf($template, $expected);

        return new DtoSerializationException($message, self::NESTED_DTO_ARRAY_HAS_NULL);
    }

    public static function createCannotSerializeNullException()
    {
        return new DtoSerializationException("Cannot serialize null value", self::CANNOT_SERIALIZE_NULL);
    }

    public static function createNestedArrayOfDtosHadDifferentObjectException($expected, $actual)
    {
        $template = "Expected array of DTOs of class '%s' had an object of type '%s'";

        $message = sprintf($template, $expected, $actual);

        return new DtoSerializationException($message, self::NESTED_DTO_ARRAY_HAS_WRONG_OBJECT);
    }

    public static function createNestedArrayOfDtosHadScalarValueException($expected, $scalar)
    {
        $template = "Expected array of DTOs of class '%s' had a scalar value: '%s'";

        $message = sprintf($template, $expected, $scalar);

        return new DtoSerializationException($message, self::NESTED_DTO_ARRAY_HAS_SCALAR);
    }

    public static function createCannotSerializeArrayWithAScalarValueException($scalar)
    {
        $template = "Encountered a scalar value in the array being serialized, the scalar value is: '%s'";

        $message = sprintf($template, $scalar);

        return new DtoSerializationException($message, self::CANNOT_SERIALIZE_ARRAY_WITH_SCALAR);
    }

    public static function createCannotSerializeArrayWithANullValueException()
    {
        $template = "Encountered null value in the array being serialized,.";

        $message = sprintf($template);

        return new DtoSerializationException($message, self::CANNOT_SERIALIZE_ARRAY_WITH_NULL);
    }
}
