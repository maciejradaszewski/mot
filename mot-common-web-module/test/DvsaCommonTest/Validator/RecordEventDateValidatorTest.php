<?php
/**
 * Created by PhpStorm.
 * User: chrislo
 * Date: 11/09/15
 * Time: 12:13
 */

namespace ApplicationTest\Validator;

use DvsaCommon\InputFilter\Event\RecordInputFilter;
use DvsaCommon\Validator\RecordEventDateValidator;

/**
 * @group validators
 */
class RecordEventDateValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var RecordEventDateValidator */
    private $object;

    public function setUp()
    {
        $this->object = new RecordEventDateValidator();
    }

    public function testTodayIsValid()
    {
        $inputDate = new \DateTime();
        $this->assertTrue($this->object->isValid($this->dateToArray($inputDate)));
    }

    public function testTomorrowIsInvalid()
    {
        $inputDate = new \DateTime();
        $inputDate->add(new \DateInterval('P1D'));
        $this->assertFalse($this->object->isValid($this->dateToArray($inputDate)));
    }

    public function testYesterdayIsValid()
    {
        $inputDate = new \DateTime();
        $inputDate->sub(new \DateInterval('P1D'));
        $this->assertTrue($this->object->isValid($this->dateToArray($inputDate)));
    }

    public function testPre1900IsInvalid()
    {
        $inputDate = new \DateTime("1899-12-31");
        $this->assertFalse($this->object->isValid($this->dateToArray($inputDate)));
    }

    public function test1900IsInvalid()
    {
        $inputDate = new \DateTime("1900-01-01");
        $this->assertTrue($this->object->isValid($this->dateToArray($inputDate)));
    }

    protected function dateToArray($date)
    {
        return [
            RecordInputFilter::FIELD_DAY => $date->format('d'),
            RecordInputFilter::FIELD_MONTH => $date->format('m'),
            RecordInputFilter::FIELD_YEAR => $date->format('Y')
        ];
    }
}