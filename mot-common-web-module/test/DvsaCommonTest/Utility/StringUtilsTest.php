<?php

namespace DvsaCommonTest\Utility;

use DvsaCommon\Utility\StringUtils;

class StringUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsTrueWhenHaystackStartsWithNeedle()
    {
        $start = "lorum";
        $haystack = "lorum ipsum";

        $this->assertTrue(StringUtils::startsWith($haystack, $start));
    }

    public function testReturnsFalseWhenHaystackDoesNotStartWithNeedle()
    {
        $needle = "ipsum";
        $haystack = "lorum ipsum";

        $this->assertFalse(StringUtils::startsWith($haystack, $needle));
    }

    public function testReturnsTrueWhenHaystackEndsWithNeedle()
    {
        $needle = "ipsum";
        $haystack = "lorum ipsum";

        $this->assertTrue(StringUtils::endsWith($haystack, $needle));
    }

    public function testReturnsFalseWhenHaystackDoesNotEndWithNeedle()
    {
        $needle = "lorum";
        $haystack = "lorum ipsum";

        $this->assertFalse(StringUtils::endsWith($haystack, $needle));
    }
}
