<?php

namespace DvsaCommonTest\Validator;

use DvsaCommon\Validator\AddressValidator;
use PHPUnit_Framework_TestCase;

class AddressValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AddressValidator
     */
    private $validator;

    /**
     * @return null
     */
    public function setUp()
    {
        $this->validator = new AddressValidator;
    }

    /**
     * @dataProvider testValidatorDataProvider
     *
     * @param array $addressData
     * @param array $errorMessages
     */
    public function testValidator(array $addressData, array $errorMessages = [])
    {
        $this->assertSame(empty($errorMessages), $this->validator->isValid($addressData));
        $this->assertSame($errorMessages, array_values($this->validator->getMessages()));
    }

    /**
     * @return array
     */
    public function testValidatorDataProvider()
    {
        return [
            // Valid
            [['firstLine' => '11 Some Street',
                'secondLine' => 'Some Building',
                'thirdLine' => 'Some Area',
                'townOrCity' => 'Some City',
                'country' => 'Some Country',
                'postcode' => 'NG1 6LP']],

            // First line empty
            [
                ['firstLine' => '',
                'secondLine' => 'Some Building',
                'thirdLine' => 'Some Area',
                'townOrCity' => 'Some City',
                'country' => 'Some Country',
                'postcode' => 'NG1 6LP'],
                [AddressValidator::MSG_FIRST_LINE_IS_EMPTY],
            ],

            // Town/city empty
            [['firstLine' => '11 Some Street',
                'secondLine' => 'Some Building',
                'thirdLine' => 'Some Area',
                'townOrCity' => '',
                'country' => 'Some Country',
                'postcode' => 'NG1 6LP'],
                [AddressValidator::MSG_TOWN_OR_CITY_IS_EMPTY]],

            // Postcode empty
            [['firstLine' => '11 Some Street',
                'secondLine' => 'Some Building',
                'thirdLine' => 'Some Area',
                'townOrCity' => '',
                'country' => 'Some Country',
                'postcode' => 'NG1 6LP'],
                [AddressValidator::MSG_TOWN_OR_CITY_IS_EMPTY]],

            // Postcode invalid
            [['firstLine' => '11 Some Street',
                'secondLine' => 'Some Building',
                'thirdLine' => 'Some Area',
                'townOrCity' => 'Some City',
                'country' => 'Some Country',
                'postcode' => 'xxxxxxxxxx'],
                [AddressValidator::MSG_INVALID_POST_CODE]],
        ];
    }
}