<?php

namespace DvsaCommonTest\InputFilter\Account;

use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Validator\PasswordValidator;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;

class ChangePasswordInputFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInputFilterReturnsErrorMessageWhenOldPasswordIsInvalid()
    {
        $data = [
            ChangePasswordInputFilter::FIELD_OLD_PASSWORD => '',
            ChangePasswordInputFilter::FIELD_PASSWORD => 'Password1',
            ChangePasswordInputFilter::FIELD_PASSWORD_CONFIRM=> 'Password1'
        ];

        $inputFilter = new ChangePasswordInputFilter();
        $inputFilter->init();

        $inputFilter->setData($data);

        $this->assertFalse($inputFilter->isValid());

        $this->assertEquals(1, count($inputFilter->getMessages()));
        $messages = $inputFilter->getMessages();
        $msg = $messages[ChangePasswordInputFilter::FIELD_OLD_PASSWORD][NotEmpty::IS_EMPTY];
        $this->assertEquals(ChangePasswordInputFilter::MSG_OLD_PASSWORD_EMPTY, $msg);
    }

    public function testInputFilterReturnsErrorMessageWhenPasswordMatchUsername()
    {
        $username = "vts-Tester-1";

        $data = [
            ChangePasswordInputFilter::FIELD_OLD_PASSWORD => 'Password1',
            ChangePasswordInputFilter::FIELD_PASSWORD => $username,
            ChangePasswordInputFilter::FIELD_PASSWORD_CONFIRM=> $username
        ];

        $inputFilter = new ChangePasswordInputFilter($this->createIdentityProvider($username));
        $inputFilter->init();

        $inputFilter->setData($data);

        $this->assertFalse($inputFilter->isValid());

        $this->assertEquals(1, count($inputFilter->getMessages()));
        $messages = $inputFilter->getMessages();
        $msg = $messages[ChangePasswordInputFilter::FIELD_PASSWORD][PasswordValidator::MSG_USERNAME];
        $this->assertNotEmpty($msg);
    }

    private function createIdentityProvider($username)
    {
        $identity = XMock::of(MotIdentityInterface::class);
        $identity
            ->expects($this->any())
            ->method("getUsername")
            ->willReturn($username);

        $identityProvider = XMock::of(MotIdentityProviderInterface::class);
        $identityProvider
            ->expects($this->any())
            ->method("getIdentity")
            ->willReturn($identity);

        return $identityProvider;
    }
}
