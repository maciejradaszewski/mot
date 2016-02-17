<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\CountryInput;
use Zend\Validator\StringLength;

class CountryInputTest extends AepInput
{
    /**
     * @var CountryInput
     */
    private $input;

    protected function setUp()
    {
        $this->input = new CountryInput();
    }

    /**
     * @dataProvider getValidData
     * @param $value
     */
    public function testValidData($value)
    {
        $this->input->setValue($value);

        $this->assertTrue($this->input->isValid());
    }

    public function getValidData()
    {
        return [
            ["England"],
            [""],
            [" "],
            [$this->createString(CountryInput::MAX_LENGTH), " "],
            [$this->createString(CountryInput::MAX_LENGTH)]
        ];
    }

    /**
     * @dataProvider getInvalidData
     * @param $value
     * @param $expectedMessages
     */
    public function testInvalidData($value, $expectedMessages)
    {
        $this->input->setValue($value);

        $this->assertFalse($this->input->isValid());

        $messages = $this->input->getMessages();
        $this->assertCount(count($expectedMessages), $messages);
        $this->assertEquals($expectedMessages, $messages);
    }

    public function getInvalidData()
    {
        return [
            [
                $this->createString(CountryInput::MAX_LENGTH + 1, " "),
                [
                    StringLength::TOO_LONG => $this->tooLongMsg(CountryInput::MSG_TOO_LONG, CountryInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(CountryInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(CountryInput::MSG_TOO_LONG, CountryInput::MAX_LENGTH)]
            ],

        ];
    }
}