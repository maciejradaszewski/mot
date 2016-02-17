<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\TownInput;
use Zend\Validator\StringLength;

class TownInputTest extends AepInput
{
    /** @var  TownInput */
    private $input;

    protected function setUp()
    {
        $this->input = new TownInput();;
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
            ["Bristol"],
            [$this->createString(TownInput::MAX_LENGTH)]
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
                [ NotEmpty::IS_EMPTY => TownInput::MSG_EMPTY ]
            ],
            [
                " ",
                [ NotEmpty::IS_EMPTY => TownInput::MSG_EMPTY ]
            ],
            [
                $this->createString(TownInput::MAX_LENGTH, " "),
                [ NotEmpty::IS_EMPTY => TownInput::MSG_EMPTY ]
            ],
            [
                $this->createString(TownInput::MAX_LENGTH + 1, " "),
                [
                    NotEmpty::IS_EMPTY => TownInput::MSG_EMPTY,
                    StringLength::TOO_LONG => $this->tooLongMsg(TownInput::MSG_TOO_LONG, TownInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(TownInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(TownInput::MSG_TOO_LONG, TownInput::MAX_LENGTH)]
            ],

        ];
    }
}