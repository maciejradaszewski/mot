<?php

namespace ApplicationTest\InputFilter\Event;

use DvsaCommon\InputFilter\Event\OutcomeInputFilter;
use DvsaCommon\Factory\InputFilter\Event\OutcomeInputFilterFactory;
use DvsaCommon\Enum\EventOutcomeCode;
use DvsaCommonTest\Bootstrap;

class OutcomeInputFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var OutcomeInputFilter */
    private $subject;

    public function setUp()
    {
        $factory = new OutcomeInputFilterFactory();
        $this->subject = $factory->createService(Bootstrap::getServiceManager());
    }

    public function testOutcomeFilterFactory()
    {
        $this->assertInstanceOf(
            OutcomeInputFilter::class,
            $this->subject
        );
    }

    /**
     * @param string[] $data Represent input fields name and value
     * @param boolean $isValid Expected state
     * @dataProvider dpDataAndExpectedResults
     */
    public function testValidators($data, $isValid)
    {
        $this->subject->setData([OutcomeInputFilter::FIELD_OUTCOME => $data]);

        $this->assertSame($isValid, $this->subject->isValid());
        $errorMessages = $this->subject->getMessages();
        if(true === $isValid) {
            $this->assertEmpty($errorMessages);
        } else {
            $this->assertNotEmpty($errorMessages);
        }
    }

    public function dpDataAndExpectedResults()
    {
        return [
            ['', false],
            [null, false],
            ['BADCODE', false],
            [EventOutcomeCode::APPRJ, true],
        ];
    }

}