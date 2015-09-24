<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\InputFilter\Registration;

use DvsaCommon\Factory\InputFilter\Registration\PasswordInputFilterFactory;
use DvsaCommon\InputFilter\Registration\PasswordInputFilter;
use DvsaCommon\Validator\PasswordValidator;
use DvsaCommonTest\Bootstrap;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class PasswordInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PasswordInputFilter */
    private $subject;

    public function setUp()
    {
        $factory = new PasswordInputFilterFactory();
        $this->subject = $factory->createService(Bootstrap::getServiceManager());
    }

    public function testInputFilterFactory()
    {
        $this->assertInstanceOf(
            PasswordInputFilter::class,
            $this->subject
        );
    }

    private static $counter= 1;

    /**
     * @param string[] $data Represent input fields name and value
     * @param boolean $isValid Expected state
     * @param array $messages Nested array of field names and related messages
     * @dataProvider dpDataAndExpectedResults
     */
    public function testValidators($data, $isValid, $errorMessages)
    {
        $this->subject->setData($data);

        $validationResult = $this->subject->isValid();

        $this->assertEquals($errorMessages, $this->subject->getMessages(), 'failed on '. self::$counter++);

        $this->assertSame($isValid, $validationResult);
    }

    public function dpDataAndExpectedResults()
    {

        /**
         * Preparing expected messages
         */
        $passwordValidator = new PasswordValidator();
        $messageTemplate = $passwordValidator->getMessageTemplates();
        $expMsgMin = str_replace( '%minChar%', $passwordValidator->min, $messageTemplate['msgMinChar']);
        $expMsgMax = str_replace( '%maxChar%', $passwordValidator->max, $messageTemplate['msgMaxChar']);
        $expMsgMinDigit = str_replace( '%minNumber%', $passwordValidator->minDigit, $messageTemplate['msgDigit']);
        $expMsgCase = $messageTemplate['msgUpperAndLowerCase'];

        $passwordValidator->max;

        return [
            [
                'data' => $this->prepareData(
                    'Password1',
                    'Password1'
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessages(
                    [],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    '',
                    ''
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        NotEmpty::IS_EMPTY => PasswordInputFilter::MSG_PASSWORD_EMPTY,
                        PasswordValidator::MSG_MIN_CHAR => $expMsgMin,
                        PasswordValidator::MSG_DIGIT => $expMsgMinDigit,
                        PasswordValidator::MSG_UPPER_AND_LOWERCASE => $expMsgCase,
                    ],
                    [NotEmpty::IS_EMPTY => PasswordInputFilter::MSG_PASSWORD_CONFIRM_EMPTY]
                ),
            ],
            [
                'data' => $this->prepareData(
                    'pass',
                    'Password'
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        PasswordValidator::MSG_MIN_CHAR => $expMsgMin,
                        PasswordValidator::MSG_DIGIT => $expMsgMinDigit,
                        PasswordValidator::MSG_UPPER_AND_LOWERCASE => $expMsgCase,
                    ],
                    [Identical::NOT_SAME => PasswordInputFilter::MSG_PASSWORD_CONFIRM_DIFFER]
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('P', $passwordValidator->max + 1),
                    str_repeat('P', $passwordValidator->max + 1)
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessages(
                    [
                        PasswordValidator::MSG_MAX_CHAR => $expMsgMax,
                        PasswordValidator::MSG_DIGIT => $expMsgMinDigit,
                        PasswordValidator::MSG_UPPER_AND_LOWERCASE => $expMsgCase,
                    ],
                    []
                ),
            ],
            [
                'data' => $this->prepareData(
                    str_repeat('P', $passwordValidator->max -3) . 'p12',
                    str_repeat('P', $passwordValidator->max -3) . 'p12'
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessages(
                    [],
                    []
                ),
            ],
        ];
    }

    /**
     * @param $password
     * @param $passwordConfirm
     * @return array
     */
    public function prepareData($password, $passwordConfirm)
    {
        return [
            PasswordInputFilter::FIELD_PASSWORD => $password,
            PasswordInputFilter::FIELD_PASSWORD_CONFIRM => $passwordConfirm,
        ];
    }

    /**
     * @param string[] $password
     * @param string[] $passwordConfirm
     * @return array
     */
    public function prepareMessages($password = [], $passwordConfirm = [])
    {
        $messages = [];

        if (!empty($password)) {
            $messages[PasswordInputFilter::FIELD_PASSWORD] = $password;
        }
        if (!empty($passwordConfirm)) {
            $messages[PasswordInputFilter::FIELD_PASSWORD_CONFIRM] = $passwordConfirm;
        }

        return $messages;
    }
}
