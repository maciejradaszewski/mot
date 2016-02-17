<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine1Input;
use Zend\Validator\StringLength;

class AddressLine1InputTest extends AepInput
{
    /** @var  AddressLine1Input */
    private $input;

    protected function setUp()
    {
        $this->input = new AddressLine1Input();;
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
            ["address 1"],
            [$this->createString(AddressLine1Input::MAX_LENGTH)]
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
                [ NotEmpty::IS_EMPTY => AddressLine1Input::MSG_EMPTY ]
            ],
            [
                " ",
                [ NotEmpty::IS_EMPTY => AddressLine1Input::MSG_EMPTY ]
            ],
            [
                $this->createString(AddressLine1Input::MAX_LENGTH, " "),
                [ NotEmpty::IS_EMPTY => AddressLine1Input::MSG_EMPTY ]
            ],
            [
                $this->createString(AddressLine1Input::MAX_LENGTH + 1, " "),
                [
                    NotEmpty::IS_EMPTY => AddressLine1Input::MSG_EMPTY,
                    StringLength::TOO_LONG => $this->tooLongMsg(AddressLine1Input::MSG_TOO_LONG, AddressLine1Input::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(AddressLine1Input::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(AddressLine1Input::MSG_TOO_LONG, AddressLine1Input::MAX_LENGTH)]
            ],

        ];
    }
}