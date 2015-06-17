<?php

namespace DvsaMotTest\Model;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationClass1And2Dto;

/**
 * Unit tests for BrakeEffort
 */
class BrakeTestResultClass1And2Test extends \PHPUnit_Framework_TestCase
{
    public function test_getId_shouldReturnCorrectId()
    {
        $this->assertEquals('control1EffortFront', BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_FRONT);
        $this->assertEquals('control1LockFront', BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_LOCK_FRONT);
        $this->assertEquals('control1EffortRear', BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_REAR);
        $this->assertEquals('control1LockRear', BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_LOCK_REAR);
        $this->assertEquals('control1EffortSidecar', BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_SIDECAR);
        $this->assertEquals(
            'control1BrakeEfficiency',
            BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_BRAKE_EFFICIENCY
        );

        $this->assertEquals('control2EffortFront', BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_FRONT);
        $this->assertEquals('control2LockFront', BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_LOCK_FRONT);
        $this->assertEquals('control2EffortRear', BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_REAR);
        $this->assertEquals('control2LockRear', BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_LOCK_REAR);
        $this->assertEquals('control2EffortSidecar', BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_SIDECAR);
        $this->assertEquals(
            'control2BrakeEfficiency',
            BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_BRAKE_EFFICIENCY
        );

        $this->assertEquals('gradientControl1', BrakeTestResultClass1And2ViewModel::NAME_GRADIENT_CONTROL_1);
        $this->assertEquals('gradientControl2', BrakeTestResultClass1And2ViewModel::NAME_GRADIENT_CONTROL_2);
    }

    public function test_getControlNumberText_shouldReturnCorrectValue()
    {
        $this->assertEquals('one', BrakeTestConfigurationClass3AndAboveHelper::COUNT_ONE);
        $this->assertEquals('two', BrakeTestConfigurationClass3AndAboveHelper::COUNT_TWO);
    }

    public function test_getters()
    {
        $brakeTestResult = new BrakeTestResultClass1And2ViewModel(
            (new BrakeTestConfigurationClass1And2Dto()),
            [
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_FRONT => 1,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_LOCK_FRONT => 2,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_REAR => 3,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_LOCK_REAR => 4,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_EFFORT_SIDECAR => 5,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_1_BRAKE_EFFICIENCY => 6,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_FRONT => 11,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_LOCK_FRONT => 12,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_REAR => 13,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_LOCK_REAR => 14,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_EFFORT_SIDECAR => 15,
                BrakeTestResultClass1And2ViewModel::ID_CONTROL_2_BRAKE_EFFICIENCY => 16,
            ]
        );

        $this->assertEquals(1, $brakeTestResult->getEffortFront1());
        $this->assertEquals(2, $brakeTestResult->getLockFront1());
        $this->assertEquals(3, $brakeTestResult->getEffortRear1());
        $this->assertEquals(4, $brakeTestResult->getLockRear1());
        $this->assertEquals(5, $brakeTestResult->getEffortSidecar1());
        $this->assertEquals(6, $brakeTestResult->getEfficiency1());

        $this->assertEquals(11, $brakeTestResult->getEffortFront2());
        $this->assertEquals(12, $brakeTestResult->getLockFront2());
        $this->assertEquals(13, $brakeTestResult->getEffortRear2());
        $this->assertEquals(14, $brakeTestResult->getLockRear2());
        $this->assertEquals(15, $brakeTestResult->getEffortSidecar2());
        $this->assertEquals(16, $brakeTestResult->getEfficiency2());
    }
}
