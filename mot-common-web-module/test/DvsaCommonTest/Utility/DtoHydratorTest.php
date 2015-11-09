<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Dto\JsonUnserializable;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonTest\TestUtils\SampleDto;
use DvsaCommonTest\TestUtils\SampleDtoWithNestedValues;
use JsonSerializable;
use PHPUnit_Framework_TestCase;

/**
 * Class DtoHydratorTest.
 */
class DtoHydratorTest extends PHPUnit_Framework_TestCase
{
    public function testHydrationOfDtoWithSimpleValuesWorks()
    {
        $sampleName = 'kitty';

        $sourceDto = new SampleDto();
        $sourceDto->setName($sampleName);

        /** @var SampleDto $hydratedDto */
        $hydratedDto = $this->extractAndHydrate($sourceDto);

        $this->assertEquals($sampleName, $hydratedDto->getName());
    }

    public function testHydrationOfDtoWithArrayWorks()
    {
        $sampleArray = ['dog', 'cat'];

        $sourceDto = new SampleDto();
        $sourceDto->setArrayOfValues($sampleArray);

        /** @var SampleDto $hydratedDto */
        $hydratedDto = $this->extractAndHydrate($sourceDto);

        $this->assertEquals($sampleArray, $hydratedDto->getArrayOfValues());
    }

    public function testHydrationOfRawValuesReturnsThem()
    {
        $sampleName = 'kitty';

        $hydratedValue = $this->extractAndHydrate($sampleName);

        $this->assertEquals($sampleName, $hydratedValue);
    }

    public function testHydrationOfRawArraysReturnsThem()
    {
        $sampleArray = ['dog', 'cat'];

        $hydratedArray = $this->extractAndHydrate($sampleArray);

        $this->assertEquals($sampleArray, $hydratedArray);
    }

    public function testHydrationOfNestedObjects()
    {
        $nestedName = 'doggy';
        $nestedObject = new SampleDto();
        $nestedObject->setName($nestedName);

        $masterObject = new SampleDtoWithNestedValues();
        $masterObject->setNestedDto($nestedObject);

        /** @var $hydratedDto SampleDtoWithNestedValues */
        $hydratedDto = $this->extractAndHydrate($masterObject);

        $this->assertEquals($nestedName, $hydratedDto->getNestedDto()->getName());
    }

    public function testHydrationOfListObjects()
    {
        $dto1 = new SampleDto();
        $dto1->setName('kitty');

        $dto2 = new SampleDto();
        $dto2->setName('doggy');

        $dtoList = [$dto1, $dto2];

        $hydratedDtoList = $this->extractAndHydrate($dtoList);

        $this->assertEquals($dtoList, $hydratedDtoList);
    }

    public function testJsonSerializableDtoCreatesJsonEncodableString()
    {
        $dto = new JsonSerializableDto();
        $dto->setProperty('value');

        $hydrator = new DtoHydrator();
        $data = $hydrator->dtoToJson($dto);
        $this->assertEquals('value', $data['property']);
        $this->assertEquals(JsonSerializableDto::class, $data['_class']);
    }

    public function testJsonUnserializableDtoIsInitialised()
    {
        $data = [
            'property' => 'value',
            '_class'   => JsonUnserializableDto::class,
        ];

        $hydrator = new DtoHydrator();
        /** @var $dto JsonUnserializableDto */
        $dto = $hydrator->jsonToDto($data);
        $this->assertInstanceOf(JsonUnserializableDto::class, $dto);
        $this->assertEquals('value', $dto->getProperty());
    }

    private function extractAndHydrate($dto)
    {
        return $this->hydrate($this->extract($dto));
    }

    private function extract($dto)
    {
        $hydrator = new DtoHydrator(); // creating new hydrator on each call is done on purpose

        return $hydrator->extract($dto);
    }

    private function hydrate($jsonArray)
    {
        $hydrator = new DtoHydrator(); // creating new hydrator on each call is done on purpose

        return $hydrator->doHydration($jsonArray);
    }
}

class JsonSerializableDto implements JsonSerializable
{
    private $property;

    public function setProperty($value)
    {
        $this->property = $value;
    }

    public function jsonSerialize()
    {
        return ['property' => $this->property];
    }
}

class JsonUnserializableDto implements JsonUnserializable
{
    private $property;

    public function getProperty()
    {
        return $this->property;
    }

    public function jsonUnserialize(array $data)
    {
        $this->property = $data['property'];
    }
}
