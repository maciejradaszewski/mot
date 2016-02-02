<?php
namespace DvsaCommonTest\Validator;

use DvsaCommon\Validator\PersonNameValidator;
use PHPUnit_Framework_TestCase;

class NameValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PersonNameValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new PersonNameValidator();
    }

    /**
     * @dataProvider testValidatorDataProvider
     *
     * @param array $nameData
     * @param array $errorMessages
     *
     * @group wip
     */
    public function testValidator(array $nameData, array $errorMessages = [])
    {
        $this->assertSame(empty($errorMessages), $this->validator->isValid($nameData));
        $this->assertSame($errorMessages, array_values($this->validator->getMessages()));
    }

    public function testValidatorDataProvider()
    {
        return [
            // Both names valid
            [['firstName' => 'Joe', 'middleName' => 'Bloggs', 'lastName' => 'Smith']],

            // First name valid, last name missing
            [['firstName' => 'Joe', 'middleName' => 'Bloggs', 'lastName' => ''], [PersonNameValidator::MSG_LAST_NAME_IS_EMPTY]],

            // Last name valid, first name missing
            [['firstName' => '', 'middleName' => 'Bloggs', 'lastName' => 'Smith'], [PersonNameValidator::MSG_FIRST_NAME_IS_EMPTY]],

            // Both names missing
            [['firstName' => '', 'middleName' => 'Bloggs', 'lastName' => ''], [PersonNameValidator::MSG_FIRST_NAME_IS_EMPTY,
                                                     PersonNameValidator::MSG_LAST_NAME_IS_EMPTY, ]],

            // First name valid, last name too long
            [['firstName' => 'Mathieu', 'middleName' => 'Bloggs', 'lastName' => 'asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfas'],
            [PersonNameValidator::MSG_LAST_NAME_IS_TOO_LONG], ],

            // Last name valid, first name too long
            [['firstName' => 'asdfaslkfjhasldkjfhasdlkjfhasldkjfhasdlfhslads', 'middleName' => 'Bloggs', 'lastName' => 'Smith'],
            [PersonNameValidator::MSG_FIRST_NAME_IS_TOO_LONG], ],

            // Both names too long
            [['firstName' => 'asdfaslkfjhasldkjfhasdlkjfhasldkjfhasdlfhslads',
                'middleName' => 'Bloggs',
              'lastName' => 'asdasfasdfasdfasdfasdfasdfasdfajskldhfasdfasdf', ],
            [PersonNameValidator::MSG_FIRST_NAME_IS_TOO_LONG, PersonNameValidator::MSG_LAST_NAME_IS_TOO_LONG], ],

            [['firstName' => 'Joe',
                'middleName' => 'asdasfasdfasdfasdfasdfasdfasdfajskldhfasdfasdf',
                'lastName' => 'Smith', ],
                [PersonNameValidator::MSG_MIDDLE_NAME_IS_TOO_LONG], ],
        ];
    }
}
