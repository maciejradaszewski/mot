<?php

namespace DvsaCommonTest\Domain;

use DvsaCommon\Domain\MotTestType;
use DvsaCommon\Enum\MotTestTypeCode;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Smoke tests for MotTestType enum.
 */
class MotTestTypeTest extends TestCase
{
    public function testIsDemo()
    {
        $this->assertTrue(MotTestType::isDemo(MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST));
        $this->assertFalse(MotTestType::isDemo(MotTestTypeCode::NORMAL_TEST));
        $this->assertFalse(MotTestType::isDemo('anything'));
    }

    public function testIsSlotConsuming()
    {
        $this->assertTrue(MotTestType::isSlotConsuming(MotTestTypeCode::NORMAL_TEST));
        $this->assertFalse(MotTestType::isSlotConsuming(MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING));
        $this->assertFalse(MotTestType::isSlotConsuming('anything'));
    }

    public function testIsReinspection()
    {
        $this->assertTrue(MotTestType::isReinspection(MotTestTypeCode::TARGETED_REINSPECTION));
        $this->assertFalse(MotTestType::isReinspection(MotTestTypeCode::NORMAL_TEST));
        $this->assertFalse(MotTestType::isReinspection('anything'));
    }

    public function testIsStandard()
    {
        $this->assertTrue(MotTestType::isStandard(MotTestTypeCode::NORMAL_TEST));
        $this->assertTrue(MotTestType::isStandard(MotTestTypeCode::RE_TEST));
        $this->assertFalse(MotTestType::isStandard(MotTestTypeCode::TARGETED_REINSPECTION));
        $this->assertFalse(MotTestType::isStandard('anything'));
    }

    public function testIsNonMotTypes()
    {
        $this->assertTrue(MotTestType::isNonMotTypes(MotTestTypeCode::NON_MOT_TEST));
        $this->assertFalse(MotTestType::isNonMotTypes(MotTestTypeCode::NORMAL_TEST));
        $this->assertFalse(MotTestType::isNonMotTypes('anything'));
    }

    public function testIsVeAdvisory()
    {
        $this->assertTrue(MotTestType::isVeAdvisory(MotTestTypeCode::TARGETED_REINSPECTION));
        $this->assertTrue(MotTestType::isVeAdvisory(MotTestTypeCode::MOT_COMPLIANCE_SURVEY));
        $this->assertFalse(MotTestType::isVeAdvisory(MotTestTypeCode::NORMAL_TEST));
        $this->assertFalse(MotTestType::isVeAdvisory('anything'));
    }
}
