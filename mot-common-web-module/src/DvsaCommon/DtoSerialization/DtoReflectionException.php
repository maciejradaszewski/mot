<?php

namespace DvsaCommon\DtoSerialization;

class DtoReflectionException extends \Exception
{
    const MIXED_SETTER_TYPES_CODE = 1;
    const TOO_MANY_ACCESSORS_CODE = 2;
    const INVALID_NUMBER_OF_SETTER_PARAMETERS_CODE = 3;
    const INVALID_NUMBER_OF_SETTER_DOC_PARAMETERS_CODE = 4;
    const MIXED_TYPE_USED_CODE = 5;
    const INVALID_CONSTRAINT_TYPE_CODE = 6;
    const INVALID_DOC_TYPE_CODE = 7;
    const NOT_DTO_DOC_TYPE_CODE = 8;
    const ARRAY_IN_CONSTRAINT_SINGLE_IN_DOC_CODE = 9;
    const CANNOT_REGISTER_DTO_AS_CONVERTIBLE = 10;

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public static function createMixedSetterTypesException(\ReflectionMethod $setter, $classInDoc, $classInConstraint)
    {
        $template = "DTO '%s' method '%s' has different type declared in php doc and constraint. "
            . "PhpDoc says the type is '%s' while the constraint type is '%s'";

        $class = $setter->getDeclaringClass()->getName();
        $setterName = $setter->getName();

        $message = sprintf($template, $class, $setterName, $classInDoc, $classInConstraint);

        return new DtoReflectionException($message, self::MIXED_SETTER_TYPES_CODE);
    }

    public static function createTooManyAccesorsDtoReflectionException($dtoClass, $invalidProperty, array $accesors)
    {
        $messageTemplate = "DTO '%s' has to many methods to get a value for property '%s'. These are: %s";

        $accesorsAsString = implode(", ", $accesors);

        $message = sprintf($messageTemplate, $dtoClass, $invalidProperty, $accesorsAsString);

        return new DtoReflectionException($message, self::TOO_MANY_ACCESSORS_CODE);
    }

    public static function createInvalidNumberOfSetterParametersException(\ReflectionMethod $method)
    {
        $template = "DTO '%s' has a setter '%s' that has '%s' parameters. Setters in Dtos should have exactly one parameter";

        $message = sprintf($template, $method->getDeclaringClass()->getName(), $method->getName(), $method->getNumberOfParameters());

        return new DtoReflectionException($message, self::INVALID_NUMBER_OF_SETTER_PARAMETERS_CODE);
    }

    public static function createInvalidNumberOfSetterParametersInDocException(\ReflectionMethod $method)
    {
        $template = "DTO '%s' has a setter '%s' that has '%s' parameters in php doc comment. Setters in Dtos should have exactly one parameter";

        $message = sprintf($template, $method->getDeclaringClass()->getName(), $method->getName(), $method->getNumberOfParameters());

        return new DtoReflectionException($message, self::INVALID_NUMBER_OF_SETTER_DOC_PARAMETERS_CODE);
    }

    public static function createMixedTypeUsedException(\ReflectionMethod $method)
    {
        $template = "DTO '%s' has a setter '%s' that has 'mixed' parameter in php doc comment. Do not use 'mixed' in DTOs";

        $message = sprintf($template, $method->getDeclaringClass()->getName(), $method->getName());

        return new DtoReflectionException($message, self::MIXED_TYPE_USED_CODE);
    }

    public static function createInvalidConstrainTypeException(\ReflectionMethod $method, $unknownType)
    {
        $template = "DTO '%s' has a setter '%s' that has a parameter of type '%s' which is not a DTO type. "
            . "Only objects implement 'ReflectiveDtoInterface' can be used inside other DTOs.";

        $message = sprintf($template, $method->getDeclaringClass()->getName(), $method->getName(), $unknownType);

        return new DtoReflectionException($message, self::INVALID_CONSTRAINT_TYPE_CODE);
    }

    public static function createInvalidDocTypeException(\ReflectionMethod $method, $unknownType)
    {
        $template = "DTO '%s' has a setter '%s' that has a parameter in php doc of type '%s'. "
            . "The parameter has not been recognised as a known class. "
            . "Probably you didn't put the full class path (along with the namespace) in the php doc. "
            . "On the other hand if '%s' is a scalar type (like string or int) then you can add it to '%s'.";

        $message = sprintf(
            $template,
            $method->getDeclaringClass()->getName(),
            $method->getName(),
            $unknownType,
            $unknownType,
            ScalarTypesList::class
        );

        return new DtoReflectionException($message, self::INVALID_DOC_TYPE_CODE);
    }

    public static function createNotDtoDocTypeException(\ReflectionMethod $method, $notDtoClass)
    {
        $template = "DTO '%s' has a setter '%s' that has a parameter in php doc of type '%s' which is not a DTO or convertible type. "
            . "DTO can only have nested objects that are also DTOs or convertible types. "
            . "Please use only objects that implement 'ReflectiveDtoInterface' or are registered in '%s'.";

        $message = sprintf (
            $template,
            $method->getDeclaringClass()->getName(),
            $method->getName(),
            $notDtoClass,
            DtoConvertibleTypesRegistry::class
        );

        return new DtoReflectionException($message, self::NOT_DTO_DOC_TYPE_CODE);
    }

    public static function createArrayInConstraintSingleInDocException(\ReflectionMethod $method)
    {
        $template = "DTO '%s' has a setter '%s' that has an array in parameter constraint "
            . "and at the same time single DTO object in comments.";

        $message = sprintf($template, $method->getDeclaringClass()->getName(), $method->getName());

        return new DtoReflectionException($message, self::ARRAY_IN_CONSTRAINT_SINGLE_IN_DOC_CODE);
    }

    public static function createCannotRegisterDtoAsConvertible($class)
    {
        $template = "Cannot register DTOs as convertible types. "
            . "Attempt was made to register DTO '%s'";

        $message = sprintf($template, $class);

        return new DtoReflectionException($message, self::CANNOT_REGISTER_DTO_AS_CONVERTIBLE);
    }
}
