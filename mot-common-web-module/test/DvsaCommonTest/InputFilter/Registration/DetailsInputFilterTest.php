<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\InputFilter\Registration;

use DvsaCommon\Factory\InputFilter\Registration\DetailsInputFilterFactory;
use DvsaCommon\InputFilter\Registration\DetailsInputFilter;
use DvsaCommon\Validator\EmailAddressValidator;
use DvsaCommonTest\Bootstrap;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;

class DetailsInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var DetailsInputFilter */
    private $subject;

    public function setUp()
    {
        $factory = new DetailsInputFilterFactory();
        $this->subject = $factory->createService(Bootstrap::getServiceManager());
    }

    public function testInputFilterFactory()
    {
        $this->assertInstanceOf(
            DetailsInputFilter::class,
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
                // Test Valid Data
                'data' => $this->prepareData(
                    'Joe',
                    'Light',
                    'Brown',
                    'detailsinputfiltertest@' . EmailAddressValidator::TEST_DOMAIN,
                    'detailsinputfiltertest@' . EmailAddressValidator::TEST_DOMAIN
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
                // Test Empty Data Set
                'data' => $this->prepareData(
                    '',
                    '',
                    '',
                    '',
                    ''
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_FIRST_NAME_EMPTY,
                        Regex::NOT_MATCH => DetailsInputFilter::MSG_NAME_NO_PATTERN_MATCH,
                    ],
                    [],
                    [
                        NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_LAST_NAME_EMPTY,
                        Regex::NOT_MATCH => DetailsInputFilter::MSG_NAME_NO_PATTERN_MATCH,
                    ],
                    [
                        EmailAddressValidator::INVALID_FORMAT => DetailsInputFilter::MSG_EMAIL_INVALID,
                        NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_EMAIL_INVALID,
                    ],
                    [
                        NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_EMAIL_CONFIRM_EMPTY,
                    ]
                ),
            ],
            [
                // Test Invalid Name
                'data' => $this->prepareData(
                    'J0£',
                    'L1ght',
                    'Br0wn',
                    'detailsinputfiltertest@' . EmailAddressValidator::TEST_DOMAIN,
                    'detailsinputfiltertest@' . EmailAddressValidator::TEST_DOMAIN
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [Regex::NOT_MATCH => DetailsInputFilter::MSG_NAME_NO_PATTERN_MATCH],
                    [Regex::NOT_MATCH => DetailsInputFilter::MSG_NAME_NO_PATTERN_MATCH],
                    [Regex::NOT_MATCH => DetailsInputFilter::MSG_NAME_NO_PATTERN_MATCH],
                    [],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    'Joe',
                    'light',
                    'Brown',
                    'detailsinputfiltertest@' . EmailAddressValidator::TEST_DOMAIN,
                    'detailsinputfiltertestdifferent@' . EmailAddressValidator::TEST_DOMAIN
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [],
                    [],
                    [],
                    [],
                    [Identical::NOT_SAME => DetailsInputFilter::MSG_EMAIL_CONFIRM_DIFFER,]
                ),
            ],
        ];
    }

    /**
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     * @param string $email
     * @param string $emailConfirm
     * @return array
     */
    public function prepareData(
        $firstName,
        $middleName,
        $lastName,
        $email,
        $emailConfirm
    ) {
        return [
            DetailsInputFilter::FIELD_FIRST_NAME => $firstName,
            DetailsInputFilter::FIELD_MIDDLE_NAME => $middleName,
            DetailsInputFilter::FIELD_LAST_NAME => $lastName,
            DetailsInputFilter::FIELD_EMAIL => $email,
            DetailsInputFilter::FIELD_EMAIL_CONFIRM => $emailConfirm,
        ];
    }

    /**
     * @param string[] $firstNameMessages
     * @param string[] $middleNameMessages
     * @param string[] $lastNameMessages
     * @param string[] $emailMessages
     * @param string[] $emailConfirmMessages
     * @return array
     */
    public function prepareMessages(
        $firstNameMessages = [],
        $middleNameMessages = [],
        $lastNameMessages = [],
        $emailMessages = [],
        $emailConfirmMessages = []
    ) {
        $messages = [];

        if (!empty($firstNameMessages)) {
            $messages[DetailsInputFilter::FIELD_FIRST_NAME] = $firstNameMessages;
        }
        if (!empty($middleNameMessages)) {
            $messages[DetailsInputFilter::FIELD_MIDDLE_NAME] = $middleNameMessages;
        }
        if (!empty($lastNameMessages)) {
            $messages[DetailsInputFilter::FIELD_LAST_NAME] = $lastNameMessages;
        }
        if (!empty($emailMessages)) {
            $messages[DetailsInputFilter::FIELD_EMAIL] = $emailMessages;
        }
        if (!empty($emailConfirmMessages)) {
            $messages[DetailsInputFilter::FIELD_EMAIL_CONFIRM] = $emailConfirmMessages;
        }

        return $messages;
    }
}
