<?php

namespace DvsaCommonTest\Guid;

use DvsaCommon\Guid\Guid;
use PHPUnit_Framework_TestCase;

/**
 * Class GuidTest
 *
 * @package DvsaCommonTest\Guid
 */
class GuidTest extends PHPUnit_Framework_TestCase
{

    public function testNonNullValueReturned()
    {
        $this->assertNotNull(Guid::newGuid());
    }

    public function testCorrectFormatReturned()
    {
        $this->assertTrue(preg_match("/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i", Guid::newGuid()) == 1);
    }

}
