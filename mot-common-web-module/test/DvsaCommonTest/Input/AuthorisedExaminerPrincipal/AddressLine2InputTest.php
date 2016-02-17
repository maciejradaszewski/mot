<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine2Input;
use Zend\Validator\StringLength;

class AddressLine2InputTest extends AepInput
{
    /**
     * @var AddressLine2Input
     */
    private $input;

    protected function setUp()
    {
        $this->input = new AddressLine2Input();
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
            ["address 2"],
            [""],
            [" "],
            [$this->createString(AddressLine2Input::MAX_LENGTH), " "],
            [$this->createString(AddressLine2Input::MAX_LENGTH)]
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
                $this->createString(AddressLine2Input::MAX_LENGTH + 1, " "),
                [
                    StringLength::TOO_LONG => $this->tooLongMsg(AddressLine2Input::MSG_TOO_LONG, AddressLine2Input::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(AddressLine2Input::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(AddressLine2Input::MSG_TOO_LONG, AddressLine2Input::MAX_LENGTH)]
            ],

        ];
    }
}