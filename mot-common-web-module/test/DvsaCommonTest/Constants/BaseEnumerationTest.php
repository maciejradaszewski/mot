<?php
namespace DvsaCommonTest\Constants;

use PHPUnit_Framework_TestCase;

class BaseEnumerationTest extends  PHPUnit_Framework_TestCase
{
    public function test_getAllValues_returns_all_constants_stored_in_enum()
    {
        $this->assertEquals([MockEnum::A, MockEnum::B, MockEnum::C], MockEnum::getValues());
    }

    public function test_isValid_return_true_for_values_stored_in_enum()
    {
        $this->assertTrue(MockEnum::isValid(MockEnum::A));
        $this->assertTrue(MockEnum::isValid(MockEnum::B));
        $this->assertTrue(MockEnum::isValid(MockEnum::C));
    }

    public function test_isValid_returns_false_for_values_not_stored_in_enum()
    {
        $this->assertFalse(MockEnum::isValid('D'));
        $this->assertFalse(MockEnum::isValid('Timmah'));
    }
}
