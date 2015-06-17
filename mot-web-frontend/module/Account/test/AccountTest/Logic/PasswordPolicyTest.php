<?php

namespace AccountTest\Logic;

use Account\Logic\PasswordPolicy;
use Account\ViewModel\ChangePasswordFormModel;
use DvsaCommonTest\TestUtils\XMock;

class PasswordPolicyTest extends \PHPUnit_Framework_TestCase
{
    /** @var  PasswordPolicyTestFormModel */
    private $formModel;

    public function setUp()
    {
        $this->formModel = XMock::of(PasswordPolicyTestFormModel::class, ['isValid','addError','hasErrors']);
    }

    public function testPasswordIsNotUsernameRaisesError()
    {
        $policy = new PasswordPolicy(
            $this->formModel,
            "theusername",
            "theusername",
            "idontmatterforthistest"
        );

        $this->formModel->expects($this->once())
            ->method('addError')
            ->with(
                ChangePasswordFormModel::FIELD_PASS,
                PasswordPolicy::ERR_NOT_USERNAME
            );

        $this->assertFalse($policy->enforce());
    }

    public function testEmptyFirstPasswordFailsPolicyCheck()
    {
        $policy = new PasswordPolicy(
            $this->formModel,
            "theusername",
            "",
            "idontmatterforthistest"
        );

        $this->formModel->expects($this->once())
            ->method('addError')
            ->with(
                ChangePasswordFormModel::FIELD_PASS,
                PasswordPolicy::ERR_REQUIRED
            );

        $this->assertFalse($policy->enforce());
    }

    public function testFailsIfPasswordsAreDifferent()
    {
        $policy = new PasswordPolicy(
            $this->formModel,
            "theusername",
            "password-a",
            "password-A-oops!"
        );

        $this->formModel->expects($this->once())
            ->method('addError')
            ->with(
                ChangePasswordFormModel::FIELD_PASS_CONFIRM,
                PasswordPolicy::ERR_NOT_SAME
            );

        $this->assertFalse($policy->enforce());
    }
}

