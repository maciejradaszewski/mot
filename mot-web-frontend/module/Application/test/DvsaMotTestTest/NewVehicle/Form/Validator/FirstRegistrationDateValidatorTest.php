<?php
namespace DvsaMotTestTest\Form\Validator;

use DvsaMotTest\NewVehicle\Form\Validator\FirstRegistrationDateValidator;
use PHPUnit_Framework_TestCase;

/**
 * Tests for the FirstRegistrationDateValidator class
 */
class FirstRegistrationDateValidatorTest extends PHPUnit_Framework_TestCase
{
    public function test_isValid_returns_false_for_date_in_future()
    {
        $tomorrow = (new \DateTime())->add(new \DateInterval('P1D'));

        $input = $this->dateToFormattedArray($tomorrow);

        $this->assertFalse((new FirstRegistrationDateValidator())->isValid($input));
    }

    public function test_isValid_returns_false_for_dates_before_1800()
    {
        $input = $this->dateToFormattedArray(new \DateTime('1799-04-11'));

        $this->assertFalse((new FirstRegistrationDateValidator())->isValid($input));
    }

    public function test_isValid_returns_false_for_invalid_date_format()
    {
        $invalidDate = [
            'year'  => '1799',
            'month' => '6',
            'day'   => '31'
        ];
        $missingPart = ['year' => '2001', 'day' => '23'];
        $rubbish = 'asd';

        $v = new FirstRegistrationDateValidator();

        $this->assertFalse($v->isValid($invalidDate));
        $this->assertFalse($v->isValid($missingPart));
        $this->assertFalse($v->isValid($rubbish));
    }

    public function test_isValid_returns_true_for_date_in_recent_past()
    {
        $oneYearAgo = (new \DateTime())->sub(new \DateInterval('P1Y'));
        $input = $this->dateToFormattedArray($oneYearAgo);

        $this->assertTrue((new FirstRegistrationDateValidator())->isValid($input), $oneYearAgo->format('Y'));
    }

    private function dateToFormattedArray(\DateTime $date)
    {
        return [
            'year'  => $date->format('Y'),
            'month' => $date->format('m'),
            'day'   => $date->format('d'),
        ];
    }
}
