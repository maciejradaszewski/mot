<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\InputFilter\Registration;

use DvsaCommon\Factory\InputFilter\Registration\DetailsInputFilterFactory;
use DvsaCommon\InputFilter\Registration\DetailsInputFilter;
use DvsaCommonTest\Bootstrap;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

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
                'data' => $this->prepareData(
                    'Joe',
                    'light',
                    'Brown',
                    'some@sample.com',
                    'some@sample.com'
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
                    [NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_FIRST_NAME_EMPTY],
                    [],
                    [NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_LAST_NAME_EMPTY],
                    [
                        EmailAddress::INVALID_FORMAT => DetailsInputFilter::MSG_EMAIL_INVALID,
                        NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_EMAIL_INVALID
                    ],
                    [NotEmpty::IS_EMPTY => DetailsInputFilter::MSG_EMAIL_CONFIRM_EMPTY]
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX + 1),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX + 1),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX + 1),
                    str_repeat('a', DetailsInputFilter::LIMIT_EMAIL_MAX + 1),
                    str_repeat('a', DetailsInputFilter::LIMIT_EMAIL_MAX + 1)
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        StringLength::TOO_LONG =>
                            sprintf(DetailsInputFilter::MSG_NAME_MAX, DetailsInputFilter::LIMIT_NAME_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(DetailsInputFilter::MSG_NAME_MAX, DetailsInputFilter::LIMIT_NAME_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(DetailsInputFilter::MSG_NAME_MAX, DetailsInputFilter::LIMIT_NAME_MAX)
                    ],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(DetailsInputFilter::MSG_EMAIL_MAX, DetailsInputFilter::LIMIT_EMAIL_MAX),
                        EmailAddress::INVALID_FORMAT => DetailsInputFilter::MSG_EMAIL_INVALID,
                    ],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX),
                    str_repeat('a', DetailsInputFilter::LIMIT_NAME_MAX)
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [],
                    [],
                    [],
                    [
                        EmailAddress::INVALID_FORMAT => DetailsInputFilter::MSG_EMAIL_INVALID,
                    ],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    'Joe',
                    'light',
                    'Brown',
                    'some@sample.com',
                    'somethingElse@sample.com'
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
