<?php

namespace DvsaCommonTest\Validator;

use DvsaCommon\Validator\UsernameValidator;

class UsernameValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsernameValidator
     */
    protected $validator;

    /**
     * Creates a new UsernameValidator object for each test method.
     */
    public function setUp()
    {
        $this->validator = new UsernameValidator();
    }

    /**
     * Ensures that the validator follows expected behavior.
     */
    public function testBasic()
    {
        if (PHP_VERSION_ID < 50600) {
            iconv_set_encoding('internal_encoding', 'UTF-8');
        } else {
            ini_set('default_charset', 'UTF-8');
        }

        /*
         * The elements of each array are, in order:
         *      - minimum length
         *      - maximum length
         *      - expected validation result
         *      - array of test input values
         */
        $valuesExpected = [
            [0, null, true, ['', 'a', 'ab']],
            [-1, null, true, ['']],
            [2, 2, true, ['ab', '  ']],
            [2, 2, false, ['a', 'abc']],
            [1, null, false, ['']],
            [2, 3, true, ['ab', 'abc']],
            [2, 3, false, ['a', 'abcd']],
            [3, 3, true, ['äöü']],
            [6, 6, true, ['Müller']],
        ];
        foreach ($valuesExpected as $element) {
            $validator = new UsernameValidator($element[0], $element[1]);
            foreach ($element[3] as $input) {
                $this->assertEquals($element[2], $validator->isValid($input));
            }
        }
    }

    /**
     * Ensures that getMessages() returns expected default value.
     */
    public function testGetMessages()
    {
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * Ensures that getMin() returns expected default value.
     */
    public function testGetMin()
    {
        $this->assertEquals(0, $this->validator->getMin());
    }

    /**
     * Ensures that getMax() returns expected default value.
     */
    public function testGetMax()
    {
        $this->assertEquals(null, $this->validator->getMax());
    }

    /**
     * Ensures that setMin() throws an exception when given a value greater than the maximum.
     */
    public function testSetMinExceptionGreaterThanMax()
    {
        $max = 1;
        $min = 2;
        $this->setExpectedException('Zend\Validator\Exception\InvalidArgumentException', 'The minimum must be less than or equal to the maximum length, but');
        $this->validator->setMax($max)->setMin($min);
    }

    /**
     * Ensures that setMax() throws an exception when given a value less than the minimum.
     */
    public function testSetMaxExceptionLessThanMin()
    {
        $max = 1;
        $min = 2;
        $this->setExpectedException('Zend\Validator\Exception\InvalidArgumentException', 'The maximum must be greater than or equal to the minimum length, but ');
        $this->validator->setMin($min)->setMax($max);
    }

    /**
     */
    public function testDifferentEncodingWithValidator()
    {
        if (PHP_VERSION_ID < 50600) {
            iconv_set_encoding('internal_encoding', 'UTF-8');
        } else {
            ini_set('default_charset', 'UTF-8');
        }
        $validator = new UsernameValidator(2, 2, 'UTF-8');
        $this->assertEquals(true, $validator->isValid('ab'));
        $this->assertEquals('UTF-8', $validator->getEncoding());
        $validator->setEncoding('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $validator->getEncoding());
    }

    public function testNonStringValidation()
    {
        $this->assertFalse($this->validator->isValid([1 => 1]));
    }

    public function testEqualsMessageTemplates()
    {
        $validator = $this->validator;
        $this->assertAttributeEquals($validator->getOption('messageTemplates'),
            'messageTemplates', $validator);
    }

    public function testEqualsMessageVariables()
    {
        $validator = $this->validator;
        $this->assertAttributeEquals($validator->getOption('messageVariables'),
            'messageVariables', $validator);
    }
}
