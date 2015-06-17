<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonTest\TestUtils\SampleDto;
use DvsaCommonTest\TestUtils\SampleDtoWithNestedValues;
use PHPUnit_Framework_TestCase;

/**
 * Class DtoHydratorTest
 *
 * @package DvsaCommonTest\Utility
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
