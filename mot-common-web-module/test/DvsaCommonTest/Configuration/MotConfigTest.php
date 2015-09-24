<?php

namespace ApplicationTest\Crypt\Hash;

use DvsaCommon\Configuration\ConfigurationKeyMissingException;
use DvsaCommon\Configuration\MotConfig;

class MotConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var MotConfig */
    private $motConfig;


    public function setUp()
    {
        $config = [
            'key1' => 'value1',
            'key2' => [
                'key3' => 'value3',
            ],
        ];

        $this->motConfig = new MotConfig($config);
    }

    public function testGetSingle()
    {
        $actual = $this->motConfig->get('key1');

        $this->assertEquals('value1', $actual);
    }

    public function testGetNested()
    {
        $actual = $this->motConfig->get('key2','key3');

        $this->assertEquals('value3', $actual);
    }

    /**
     * @expectedException \DvsaCommon\Configuration\ConfigurationKeyMissingException
     */
    public function testGetNotExisting()
    {
        $actual = $this->motConfig->get('key2','notexists');

        $this->assertEquals('value3', $actual);
    }

    public function testGetWithDefaultWhenExists()
    {
        $actual = $this->motConfig->withDefault('def-value')->get('key2','key3');

        $this->assertEquals('value3', $actual);
    }

    public function testGetWithDefaultWhenNotExists()
    {
        $actual = $this->motConfig->withDefault('def-value')->get('key2','notexists');

        $this->assertEquals('def-value', $actual);
    }
}
