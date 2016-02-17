<?php


namespace DvsaCommonTest\Validator;

use PHPUnit_Framework_TestCase;
use DvsaCommon\Validator\TelephoneNumberValidator;

class TelephoneNumberValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TelephoneNumberValidator
     */
    private $validator;

    /**
     * Test set up
     * @return null
     */
    public function setUp()
    {
        $this->validator = new TelephoneNumberValidator();
    }

    /**
     * @dataProvider validationInputProvider
     * @param string $telephoneNumber
     * @param array  $errorMessages
     */
    public function testValidation($telephoneNumber, array $errorMessages = [])
    {
        $this->assertSame(empty($errorMessages), $this->validator->isValid($telephoneNumber));
        $this->assertSame($errorMessages, array_values($this->validator->getMessages()));
    }

    /**
     * @return array
     */
    public function validationInputProvider()
    {
        return [
            /* Valid telephone numbers */

            // number less than 24 digits long
            ['1234'],

            // number 24 digits long
            ['123456789012345678901234'],

            // empty number
            [''],

            /* Invalid telephone numbers */

            // number more than 24 digits long
            ['1234567890123456789012345', [TelephoneNumberValidator::MSG_PHONE_NUMBER_TOO_LONG]],

        ];
    }
}
