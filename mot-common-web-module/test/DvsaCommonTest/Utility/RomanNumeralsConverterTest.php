<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\RomanNumeralsConverter;
use DvsaCommonTest\TestUtils\SampleTestObject;
use PHPUnit_Framework_TestCase;

/**
 * Class RomanNumeralsConverterTest
 *
 * @package DvsaCommonTest\Utility
 */
class RomanNumeralsConverterTest extends PHPUnit_Framework_TestCase
{
    public function testConversion()
    {
        $this->assertEquals('I', RomanNumeralsConverter::toRomanNumerals(1));
        $this->assertEquals('II', RomanNumeralsConverter::toRomanNumerals(2));
        $this->assertEquals('III', RomanNumeralsConverter::toRomanNumerals(3));
        $this->assertEquals('IV', RomanNumeralsConverter::toRomanNumerals(4));
        $this->assertEquals('V', RomanNumeralsConverter::toRomanNumerals(5));
        $this->assertEquals('VII', RomanNumeralsConverter::toRomanNumerals(7));
        $this->assertEquals('VIII', RomanNumeralsConverter::toRomanNumerals(8));
        $this->assertEquals('IX', RomanNumeralsConverter::toRomanNumerals(9));
        $this->assertEquals('XL', RomanNumeralsConverter::toRomanNumerals(40));
        $this->assertEquals('XC', RomanNumeralsConverter::toRomanNumerals(90));
        $this->assertEquals('MMMCMXCIX', RomanNumeralsConverter::toRomanNumerals(3999));
    }
}
