<?php

namespace DvsaCommonTest\DtoSerialization;

use DvsaCommon\Date\Time;
use DvsaCommon\DtoSerialization\DtoCachedReflector;
use DvsaCommon\DtoSerialization\DtoConvertibleTypesRegistry;
use DvsaCommon\DtoSerialization\DtoReflectorInterface;
use DvsaCommonTest\DtoSerialization\TestDto\TestArrayInConstraintSingleInDocDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestInvalidTypeInDocDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestManyGettersDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestMixedSetterTypesDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestMixedTypeDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestNestedDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestNotDtoParameterDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestNotDtoParameterInDocDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestTooManyParamsDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestTooManyParamsInDocDto;
use DvsaCommonTest\DtoSerialization\TestDto\TestValidDto;
use PHPUnit_Framework_TestCase;

class DtoReflectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DtoReflectorInterface
     */
    private $reflector;

    public function setUp()
    {
        $this->reflector = new DtoCachedReflector(new DtoConvertibleTypesRegistry());
    }

    public function testReflectorDoesNotReturnPropertiesThatDoNotHaveASetter()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('propertyWithoutASetter');

        $this->assertNull($matchedProperty);
    }

    public function testReflectorDoesNotReturnPropertiesThatDoNotHaveAGetter()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('propertyWithoutAGetter');

        $this->assertNull($matchedProperty);
    }

    public function testReflectorGetsTheProperDtoClassName()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $this->assertEquals(TestValidDto::class, $classReflection->getClass());
    }

    public function testReflectorExtractPropertiesWithBothSetterAndGetter()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('scalarProperty');

        $this->assertNotNull($matchedProperty);
        $this->assertEquals('scalarProperty', $matchedProperty->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReflectorThrowsExceptionForNotDtoClass()
    {
        $this->reflector->reflect(self::class);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReflectorThrowsExceptionForScalarValues()
    {
        $this->reflector->reflect("string");
    }

    public function testReflectorGetsTheCorrectDtoTypeFromConstraint()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedDtoWithConstraint');

        $this->assertEquals(TestNestedDto::class, $matchedProperty->getClass());
        $this->assertEquals('nestedDtoWithConstraint', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isDto());
    }

    public function testReflectorGetsTheCorrectDtoTypeFromDoc()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedDtoWithDoc');

        $this->assertEquals(TestNestedDto::class, $matchedProperty->getClass());
        $this->assertEquals('nestedDtoWithDoc', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isDto());
    }

    public function testReflectorGetsTheCorrectDtoTypeFromBothConstraintAndDoc()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedDtoWithDocAndConstraint');

        $this->assertEquals(TestNestedDto::class, $matchedProperty->getClass());
        $this->assertEquals('nestedDtoWithDocAndConstraint', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isDto());
    }

    public function testReflectorGetsTheCorrectConvertibleTypeFromConstraint()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedConvertibleWithConstraint');

        $this->assertEquals(\DateTime::class, $matchedProperty->getClass());
        $this->assertEquals('nestedConvertibleWithConstraint', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isConvertible());
    }

    public function testReflectorGetsTheCorrectConvertibleTypeFromDoc()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedConvertibleWithDoc');

        $this->assertEquals(\DateTime::class, $matchedProperty->getClass());
        $this->assertEquals('nestedConvertibleWithDoc', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isConvertible());
    }

    public function testReflectorGetsTheCorrectConvertibleTypeFromBothConstraintAndDoc()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedConvertibleWithDocAndConstraint');

        $this->assertEquals(\DateTime::class, $matchedProperty->getClass());
        $this->assertEquals('nestedConvertibleWithDocAndConstraint', $matchedProperty->getName());
        $this->assertTrue($matchedProperty->isConvertible());
    }

    public function testReflectorRecognisesDtoProperties()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedDtoWithConstraint');

        $this->assertTrue($matchedProperty->isDto());
        $this->assertFalse($matchedProperty->isArray());
    }

    public function testReflectorRecognisesScalarTypesProperties()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('scalarProperty');

        $this->assertTrue($matchedProperty->isScalarType());
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 1
     */
    public function testReflectorThrowsExceptionWhenSetterConstraintIsDifferentThanDoc()
    {
        $this->reflector->reflect(TestMixedSetterTypesDto::class);
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 2
     */
    public function testReflectorThrowsExceptionWhenThereAreToManyMethodsToGetAProperty()
    {
        $this->reflector->reflect(TestManyGettersDto::class);
    }

    public function testReflectorWorksWithGet()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('propertyWithGet');

        $this->assertNotNull($matchedProperty);
        $this->assertEquals('propertyWithGet', $matchedProperty->getName());
    }

    public function testReflectorWorksWithHas()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('propertyWithHas');

        $this->assertNotNull($matchedProperty);
        $this->assertEquals('propertyWithHas', $matchedProperty->getName());
    }

    public function testReflectorWorksWithIs()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('propertyWithIs');

        $this->assertNotNull($matchedProperty);
        $this->assertEquals('propertyWithIs', $matchedProperty->getName());
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 6
     */
    public function testReflectorVerifiesIfPropertiesAreDtos()
    {
        $this->reflector->reflect(TestNotDtoParameterDto::class);
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 7
     */
    public function testReflectorVerifiesIfDocScalarTypesAreKnown()
    {
        $this->reflector->reflect(TestInvalidTypeInDocDto::class);
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 8
     */
    public function testReflectorVerifiesIfDocTypesAreDtosOrConvertibles()
    {
        $this->reflector->reflect(TestNotDtoParameterInDocDto::class);
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 3
     */
    public function testReflectionFailsWhenSetterHasInvalidNumberOfParameters()
    {
        $this->reflector->reflect(TestTooManyParamsDto::class);
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 4
     */
    public function testReflectionFailsWhenSetterHasMoreThanOneParametersInDoc()
    {
        $this->reflector->reflect(TestTooManyParamsInDocDto::class);
    }

    public function testReflectionSupportsArraysOfDtosInSetters()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedDtoArray');

        $this->assertEquals(TestNestedDto::class, $matchedProperty->getClass());
        $this->assertTrue($matchedProperty->isDto());
        $this->assertTrue($matchedProperty->isArray());
    }

    public function testReflectionSupportsArraysOfConvertiblesInSetters()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('nestedConvertibleArray');

        $this->assertEquals(Time::class, $matchedProperty->getClass());
        $this->assertTrue($matchedProperty->isConvertible());
        $this->assertTrue($matchedProperty->isArray());
    }

    public function testReflectionSupportsArraysOfScalarTypesInSetters()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('scalarArray');

        $this->assertTrue($matchedProperty->isScalarType());
        $this->assertTrue($matchedProperty->isArray());
    }

    public function testReflectionSupportsArrayOfUnknownTypes()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);

        $matchedProperty = $classReflection->getProperty('unknownArray');

        $this->assertTrue($matchedProperty->isScalarType());
        $this->assertTrue($matchedProperty->isArray());
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 5
     */
    public function testReflectionDoesNotSupportMixedTypes()
    {
        $this->reflector->reflect(TestMixedTypeDto::class);
    }

    public function testReflectorTreatsScalarValuesInDocBlockAsNoneClass()
    {
        $classReflection = $this->reflector->reflect(TestValidDto::class);
        $matchedProperty = $classReflection->getProperty('scalarProperty');

        $this->assertEquals('', $matchedProperty->getClass());
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 9
     */
    public function testReflectorFailsWhenConstraintIsArrayButDocTypeIsSingleDto()
    {
        $this->reflector->reflect(TestArrayInConstraintSingleInDocDto::class);
    }
}
