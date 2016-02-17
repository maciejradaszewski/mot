<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FirstNameInput;
use Zend\Validator\StringLength;

class FirstNameInputTest extends AepInput
{
    /** @var  FirstNameInput */
    private $input;

    protected function setUp()
    {
        $this->input = new FirstNameInput();;
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
            [$this->createString(FirstNameInput::MAX_LENGTH)]
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
                "",
                [ NotEmpty::IS_EMPTY => FirstNameInput::MSG_EMPTY ]
            ],
            [
                " ",
                [ NotEmpty::IS_EMPTY => FirstNameInput::MSG_EMPTY ]
            ],
            [
                $this->createString(FirstNameInput::MAX_LENGTH, " "),
                [ NotEmpty::IS_EMPTY => FirstNameInput::MSG_EMPTY ]
            ],
            [
                $this->createString(FirstNameInput::MAX_LENGTH + 1, " "),
                [
                    NotEmpty::IS_EMPTY => FirstNameInput::MSG_EMPTY,
                    StringLength::TOO_LONG => $this->tooLongMsg(FirstNameInput::MSG_TOO_LONG, FirstNameInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(FirstNameInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(FirstNameInput::MSG_TOO_LONG, FirstNameInput::MAX_LENGTH)]
            ],

        ];
    }
}