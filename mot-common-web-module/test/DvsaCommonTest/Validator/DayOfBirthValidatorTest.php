<?php

namespace DvsaCommonTest\Validator;


use DvsaCommon\Validator\DateOfBirthValidator;

class DayOfBirthValidatorTest extends \PHPUnit_Framework_TestCase
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

    protected function dateToArray(\DateTime $date)
    {
        return [
            DateOfBirthValidator::FIELD_DAY => $date->format('d'),
            DateOfBirthValidator::FIELD_MONTH => $date->format('m'),
            DateOfBirthValidator::FIELD_YEAR => $date->format('Y')
        ];
    }
}