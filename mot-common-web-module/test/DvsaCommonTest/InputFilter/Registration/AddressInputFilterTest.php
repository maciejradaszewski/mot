<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\InputFilter\Registration;

use DvsaCommon\Factory\InputFilter\Registration\AddressInputFilterFactory;
use DvsaCommon\InputFilter\Registration\AddressInputFilter;
use DvsaCommonTest\Bootstrap;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class AddressInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var AddressInputFilter */
    private $subject;

    public function setUp()
    {
        $factory = new AddressInputFilterFactory();
        $this->subject = $factory->createService(Bootstrap::getServiceManager());
    }

    public function testInputFilterFactory()
    {
        $this->assertInstanceOf(
            AddressInputFilter::class,
            $this->subject
        );
    }

    /**
     * @param string[] $data Represent input fields name and value
     * @param boolean $isValid Expected state
     * @param array $messages Nested array of field names and related messages
     * @dataProvider dpDataAndExpectedResults
     */
    public function testValidators($data, $isValid, $errorMessages)
    {
        $this->subject->setData($data);

        $this->assertSame($isValid, $this->subject->isValid());

        $this->assertEquals($errorMessages, $this->subject->getMessages());
    }

    public function dpDataAndExpectedResults()
    {
        return [
            [
                'data' => $this->prepareData(
                    'dummy number',
                    'dummy street',
                    'dummy area',
                    'dummy city',
                    'dummy code'
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessages(
                    [],
                    [],
                    [],
                    [],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    '',
                    '',
                    '',
                    '',
                    ''
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [NotEmpty::IS_EMPTY => AddressInputFilter::MSG_ADDRESS_EMPTY],
                    [],
                    [],
                    [NotEmpty::IS_EMPTY => AddressInputFilter::MSG_TOWN_OR_CITY_EMPTY],
                    [NotEmpty::IS_EMPTY => AddressInputFilter::MSG_POSTCODE_EMPTY]
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX + 1),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX + 1),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX + 1),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX + 1),
                    str_repeat('a', AddressInputFilter::LIMIT_POSTCODE_MAX + 1)
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        StringLength::TOO_LONG =>
                            sprintf(AddressInputFilter::MSG_ADDRESS_MAX, AddressInputFilter::LIMIT_ADDRESS_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(AddressInputFilter::MSG_ADDRESS_MAX, AddressInputFilter::LIMIT_ADDRESS_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(AddressInputFilter::MSG_ADDRESS_MAX, AddressInputFilter::LIMIT_ADDRESS_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(AddressInputFilter::MSG_ADDRESS_MAX, AddressInputFilter::LIMIT_ADDRESS_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(AddressInputFilter::MSG_POSTCODE_MAX, AddressInputFilter::LIMIT_POSTCODE_MAX)
                    ]
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX),
                    str_repeat('a', AddressInputFilter::LIMIT_ADDRESS_MAX),
                    str_repeat('a', AddressInputFilter::LIMIT_POSTCODE_MAX)
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessages(
                    [],
                    [],
                    [],
                    [],
                    []
                ),
            ],
        ];
    }

    /**
     * @param string $address1
     * @param string $address2
     * @param string $address3
     * @param string $townOrCity
     * @param string $postcode
     * @return array
     */
    public function prepareData(
        $address1,
        $address2,
        $address3,
        $townOrCity,
        $postcode
    ) {
        return [
            AddressInputFilter::FIELD_ADDRESS_1 => $address1,
            AddressInputFilter::FIELD_ADDRESS_2 => $address2,
            AddressInputFilter::FIELD_ADDRESS_3 => $address3,
            AddressInputFilter::FIELD_TOWN_OR_CITY => $townOrCity,
            AddressInputFilter::FIELD_POSTCODE => $postcode,
        ];
    }

    /**
     * @param string[] $address1Messages
     * @param string[] $address2Messages
     * @param string[] $address3Messages
     * @param string[] $townOrCityMessages
     * @param string[] $postcodeMessages
     * @return array
     */
    public function prepareMessages(
        $address1Messages = [],
        $address2Messages = [],
        $address3Messages = [],
        $townOrCityMessages = [],
        $postcodeMessages = []
    ) {
        $messages = [];

        if (!empty($address1Messages)) {
            $messages[AddressInputFilter::FIELD_ADDRESS_1] = $address1Messages;
        }
        if (!empty($address2Messages)) {
            $messages[AddressInputFilter::FIELD_ADDRESS_2] = $address2Messages;
        }
        if (!empty($address3Messages)) {
            $messages[AddressInputFilter::FIELD_ADDRESS_3] = $address3Messages;
        }
        if (!empty($townOrCityMessages)) {
            $messages[AddressInputFilter::FIELD_TOWN_OR_CITY] = $townOrCityMessages;
        }
        if (!empty($postcodeMessages)) {
            $messages[AddressInputFilter::FIELD_POSTCODE] = $postcodeMessages;
        }

        return $messages;
    }
}