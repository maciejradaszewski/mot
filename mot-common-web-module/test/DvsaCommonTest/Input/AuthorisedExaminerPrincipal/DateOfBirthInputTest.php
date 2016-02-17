<?php

namespace DvsaCommonTest\Input\AuthorisedExaminerPrincipal;

use DvsaCommonTest\TestUtils\XMock;
use Zend\Validator\NotEmpty;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\DateOfBirthInput;
use DvsaCommon\Validator\DateOfBirthValidator;
use Zend\Validator\Date;
use Zend\Validator\Callback;
use DvsaCommon\Date\DateTimeApiFormat;

class DateOfBirthInputTest extends AepInput
{
    /** @var  DateOfBirthInput */
    private $input;

    protected function setUp()
    {
        $this->input = new DateOfBirthInput();
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
        $date = new \DateTime();
        $date->sub(new \DateInterval("P1D"));

        $date98yearsAgo = new \DateTime("-98 years");

        return [
            [$date->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY)],
            [$date98yearsAgo->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY)],
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
        $date = new \DateTime();
        $dateNow = $date->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY);

        $date->add(new \DateInterval("P1D"));
        $dateTomorrow = $date->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY);

        $date = new \DateTime("-99 years");
        $date99yearsAgo = $date->format(DateTimeApiFormat::FORMAT_ISO_8601_DATE_ONLY);

        return [
            [
                "",
                [
                    DateOfBirthValidator::IS_EMPTY => DateOfBirthValidator::ERR_MSG_IS_EMPTY,
                ]
            ],
            [
                " ",
                [
                    DateOfBirthValidator::IS_EMPTY => DateOfBirthValidator::ERR_MSG_IS_EMPTY ,
                    DateOfBirthValidator::IS_INVALID_FORMAT => DateOfBirthValidator::ERR_MSG_IS_INVALID_FORMAT,
                ]
            ],
            [
                "1",
                [
                    DateOfBirthValidator::IS_INVALID_FORMAT => DateOfBirthValidator::ERR_MSG_IS_INVALID_FORMAT,
                ]
            ],
            [
                "2001",
                [
                    DateOfBirthValidator::IS_INVALID_FORMAT => DateOfBirthValidator::ERR_MSG_IS_INVALID_FORMAT,
                ]
            ],
            [
                "2001-02-31",
                [
                    DateOfBirthValidator::IS_INVALID_FORMAT => DateOfBirthValidator::ERR_MSG_IS_INVALID_FORMAT,
                ]
            ],
            [
                "01-01-2001",
                [
                    DateOfBirthValidator::IS_INVALID_FORMAT => DateOfBirthValidator::ERR_MSG_IS_INVALID_FORMAT,
                ]
            ],
            [
                $dateNow,
                [
                    DateOfBirthValidator::IS_FUTURE => DateOfBirthValidator::ERR_MSG_IS_FUTURE

                ]
            ],
            [
                $dateTomorrow,
                [
                    DateOfBirthValidator::IS_FUTURE => DateOfBirthValidator::ERR_MSG_IS_FUTURE
                ]
            ],
            [
                $date99yearsAgo,
                [
                    DateOfBirthValidator::IS_OVER100 => DateOfBirthInput::MSG_OVER_99_YEARS
                ]
            ],

        ];
    }
}