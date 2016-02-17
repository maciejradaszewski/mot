<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\MiddleNameInput;
use Zend\Validator\StringLength;

class MiddleNameInputTest extends AepInput
{
    /**
     * @var MiddleNameInput
     */
    private $input;

    protected function setUp()
    {
        $this->input = new MiddleNameInput();
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
            ["John"],
            [""],
            [" "],
            [$this->createString(MiddleNameInput::MAX_LENGTH), " "],
            [$this->createString(MiddleNameInput::MAX_LENGTH)]
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
                $this->createString(MiddleNameInput::MAX_LENGTH + 1, " "),
                [
                    StringLength::TOO_LONG => $this->tooLongMsg(MiddleNameInput::MSG_TOO_LONG, MiddleNameInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(MiddleNameInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(MiddleNameInput::MSG_TOO_LONG, MiddleNameInput::MAX_LENGTH)]
            ],

        ];
    }
}