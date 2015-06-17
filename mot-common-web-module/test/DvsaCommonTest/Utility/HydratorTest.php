<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonTest\TestUtils\SampleTestObject;
use PHPUnit_Framework_TestCase;

/**
 * Class HydratorTest
 * @package DvsaCommonTest\Utility
 */
class HydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Hydrator
     */
    private $hydrator;
    private $sampleObject;

    public function setUp()
    {
        $this->hydrator = new Hydrator();
        $this->sampleObject = new SampleTestObject(1, 'Name');
    }

    public function test_extraction_should_not_return_unwanted_values()
    {
        $wantedProperty = 'name';
        $wantedProperties = [$wantedProperty];
        $unwantedProperty = 'id';

        $extractedData = $this->hydrator->extract($this->sampleObject,$wantedProperties);

        $extractedProperties = array_keys($extractedData);

        $this->assertNotContains($unwantedProperty, $extractedProperties, "Id should not have been extracted");
    }

    public function test_extraction_should_return_wanted_values()
    {
        $wantedProperty = 'name';
        $wantedProperties = [$wantedProperty];

        $extractedData = $this->hydrator->extract($this->sampleObject,$wantedProperties);

        $extractedProperties = array_keys($extractedData);

        $this->assertContains($wantedProperty, $extractedProperties, "Name should have been extracted");
    }
}
