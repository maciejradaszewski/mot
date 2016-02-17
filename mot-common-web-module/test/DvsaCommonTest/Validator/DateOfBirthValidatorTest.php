<?php

namespace DvsaCommonTest\Validator;

use DvsaCommon\Validator\DateOfBirthValidator;
use DvsaCommon\Date\DateTimeApiFormat;

class DateOfBirthValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var DateOfBirthValidator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new DateOfBirthValidator();
    }

    public function testIsEmpty_shouldFail()
    {
        $this->assertFalse($this->validator->isValid([]));
        $this->assertFalse($this->validator->isValid(""));
    }

    public function testNotValidFormat_shouldFail()
    {
        $this->assertFalse($this->validator->isValid("2-13-2001"));
    }

    public function testValidDate_shouldPass()
    {
        $nowDate = new \DateTIme('-1 day');
        $this->assertTrue(
            $this->validator->isValid($this->dateToArray($nowDate))
        );

    }

    public function testValidNowDate_shouldFail()
    {
        $nowDate = new \DateTIme('now');
        $this->assertFalse(
            $this->validator->isValid($this->dateToArray($nowDate))
        );
    }

    public function testFutureDate_shouldFail()
    {
        $futureDate = new \DateTime('+1 day');
        $this->assertFalse(
            $this->validator->isValid($this->dateToArray($futureDate))
        );
    }

    public function testOver100Years_shouldFail()
    {
        $date = new \DateTime('-100 years');
        $this->assertFalse(
            $this->validator->isValid($this->dateToArray($date))
        );
    }

    public function testOver99Years_shouldFail()
    {
        $expectedMessage = 'must be less than 99 years ago';
        $date = new \DateTime('-99 years');
        $this->validator->setDateInThePast(new \DateTime('-99 years'));
        $this->validator->setMessage($expectedMessage, DateOfBirthValidator::IS_OVER100);

        $this->assertFalse(
            $this->validator->isValid($date->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY))
        );

        $this->assertEquals([DateOfBirthValidator::IS_OVER100 => $expectedMessage], $this->validator->getMessages());
    }

    protected function dateToArray(\DateTime $date)
    {
        return [
            DateOfBirthValidator::FIELD_DAY => $date->format('d'),
            DateOfBirthValidator::FIELD_MONTH => $date->format('m'),
            DateOfBirthValidator::FIELD_YEAR => $date->format('Y')
        ];
    }
}