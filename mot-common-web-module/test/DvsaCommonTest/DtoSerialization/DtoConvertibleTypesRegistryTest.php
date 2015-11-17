<?php

namespace DvsaCommonTest\DtoSerialization;

use DvsaCommon\DtoSerialization\DtoConverterInterface;
use DvsaCommon\DtoSerialization\DtoConvertibleTypesRegistry;
use DvsaCommonTest\DtoSerialization\TestDto\SampleDto;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_TestCase;

class DtoConvertibleTypesRegistryTest extends PHPUnit_Framework_TestCase
{
    /** @var DtoConvertibleTypesRegistry */
    private $register;

    public function setUp()
    {
        $this->register = new DtoConvertibleTypesRegistry();
    }

    /**
     * @expectedException \DvsaCommon\DtoSerialization\DtoReflectionException
     * @expectedExceptionCode 10
     */
    public function testCannotRegisterDto()
    {
        $converter = XMock::of(DtoConverterInterface::class);
        $this->register->register(SampleDto::class, $converter);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailsWhenCannotFindConverter()
    {
        $this->register->getConverter('Unknown class');
    }
}
