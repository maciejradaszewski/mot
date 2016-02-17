<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FamilyNameInput;
use Zend\Validator\StringLength;

class FamilyNameInputTest extends AepInput
{
    /** @var  FamilyNameInput */
    private $input;

    protected function setUp()
    {
        $this->input = new FamilyNameInput();;
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
            ["Rambo"],
            [$this->createString(FamilyNameInput::MAX_LENGTH)]
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
                [ NotEmpty::IS_EMPTY => FamilyNameInput::MSG_EMPTY ]
            ],
            [
                " ",
                [ NotEmpty::IS_EMPTY => FamilyNameInput::MSG_EMPTY ]
            ],
            [
                $this->createString(FamilyNameInput::MAX_LENGTH, " "),
                [ NotEmpty::IS_EMPTY => FamilyNameInput::MSG_EMPTY ]
            ],
            [
                $this->createString(FamilyNameInput::MAX_LENGTH + 1, " "),
                [
                    NotEmpty::IS_EMPTY => FamilyNameInput::MSG_EMPTY,
                    StringLength::TOO_LONG => $this->tooLongMsg(FamilyNameInput::MSG_TOO_LONG, FamilyNameInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(FamilyNameInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(FamilyNameInput::MSG_TOO_LONG, FamilyNameInput::MAX_LENGTH)]
            ],

        ];
    }
}