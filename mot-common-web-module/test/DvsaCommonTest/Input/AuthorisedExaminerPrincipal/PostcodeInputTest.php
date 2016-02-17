<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\PostcodeInput;
use Zend\Validator\StringLength;

class PostcodeInputTest extends AepInput
{
    /** @var  PostcodeInput */
    private $input;

    protected function setUp()
    {
        $this->input = new PostcodeInput();;
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
            ["23-33 bl"],
            [$this->createString(PostcodeInput::MAX_LENGTH)]
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
                $this->createString(PostcodeInput::MAX_LENGTH + 1),
                [
                    StringLength::TOO_LONG => $this->tooLongMsg(PostcodeInput::MSG_TOO_LONG, PostcodeInput::MAX_LENGTH)
                ]
            ],
            [
                $this->createString(PostcodeInput::MAX_LENGTH + 1),
                [ StringLength::TOO_LONG => $this->tooLongMsg(PostcodeInput::MSG_TOO_LONG, PostcodeInput::MAX_LENGTH)]
            ],

        ];
    }
}