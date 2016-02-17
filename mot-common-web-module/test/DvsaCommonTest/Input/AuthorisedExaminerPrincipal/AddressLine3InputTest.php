<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine3Input;
use Zend\Validator\StringLength;

class AddressLine3InputTest extends AepInput
{
    /**
     * @var AddressLine3Input
     */
    private $input;

    protected function setUp()
    {
        $this->input = new AddressLine3Input();
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
            ["address 3"],
            [""],
            [" "],
            [$this->createString(AddressLine3Input::MAX_LENGTH), " "],
            [$this->createString(AddressLine3Input::MAX_LENGTH)]
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
                $this->createString(AddressLine3Input::MAX_LENGTH + 1, " "),
                [
                    StringLength::TOO_LONG => $this->tooLongMsg(AddressLine3Input::MSG_TOO_LONG, AddressLine3Input::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(AddressLine3Input::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(AddressLine3Input::MSG_TOO_LONG, AddressLine3Input::MAX_LENGTH)]
            ],

        ];
    }
}