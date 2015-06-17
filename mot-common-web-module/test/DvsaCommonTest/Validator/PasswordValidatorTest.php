<?php

namespace DvsaCommonTest\Validator;

use DvsaCommon\Validator\PasswordValidator;
use PHPUnit_Framework_TestCase;

class PasswordValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PasswordValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordValidator();
    }

    /**
     * @dataProvider validationInputProvider
     *
     * @param bool  $input
     * @param bool  $isValid
     * @param array $errorMessageKeys
     */
    public function testValidation($input, $isValid, array $errorMessageKeys)
    {
        $this->assertEquals($isValid, $this->validator->isValid($input));
        $this->assertEmpty(array_diff($errorMessageKeys, array_keys($this->validator->getMessages())));
    }

    /**
     * @return array
     */
    public function validationInputProvider()
    {
        return [
            ['Pass1', false, [PasswordValidator::MSG_MIN_CHAR]],
            ['PabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1', false, [PasswordValidator::MSG_MAX_CHAR]],
            ['Password', false, [\DvsaCommon\Validator\PasswordValidator::MSG_DIGIT]],
            ['password1', false, [\DvsaCommon\Validator\PasswordValidator::MSG_UPPER_AND_LOWERCASE]],
            ['PASSWORD1', false, [PasswordValidator::MSG_UPPER_AND_LOWERCASE]],
            ['Password1&', false, [PasswordValidator::MSG_SPECIAL_CHARS]],
            ['Password1' . PasswordValidator::OPENDJ_CS_4_SPECIAL_CHARS, true, []],
            ['Password1', true, []],
        ];
    }
}
