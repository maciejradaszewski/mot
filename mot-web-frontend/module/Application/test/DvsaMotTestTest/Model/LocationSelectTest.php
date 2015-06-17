<?php
namespace DvsaMotTest\Model;

use DvsaMotTest\Model\LocationSelect;
use PHPUnit_Framework_TestCase;

/**
 * Class LocationSelectTest
 */
class LocationSelectTest extends PHPUnit_Framework_TestCase
{
    public function testInitialState()
    {
        $locationSelect = new LocationSelect();

        $this->assertNull($locationSelect->submit, '"submit" should initially be null');
    }
}
