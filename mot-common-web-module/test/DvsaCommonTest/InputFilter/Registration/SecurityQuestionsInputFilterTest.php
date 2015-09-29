<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\InputFilter\Registration;

use DvsaCommon\Factory\InputFilter\Registration\SecurityQuestionFirstInputFilterFactory;
use DvsaCommon\Factory\InputFilter\Registration\SecurityQuestionSecondInputFilterFactory;
use DvsaCommon\InputFilter\Registration\SecurityQuestionAbstractInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionFirstInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionSecondInputFilter;
use DvsaCommonTest\Bootstrap;
use Zend\Validator\Digits;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class SecurityQuestionsInputFilterTest extends \PHPUnit_Framework_TestCase
{
    const STEP_FIRST = 'first';

    const STEP_SECOND = 'second';

    /**
     * To identify which step of security questions we are testing
     * @var string (self::STEP_FIRST|self::STEP_SECOND) default to self::STEP_FIRST
     */
    private $targetStep = 'first';

    /** @var SecurityQuestionFirstInputFilter */
    private $firstSubject;

    /** @var SecurityQuestionSecondInputFilter */
    private $secondSubject;


    public function setUp()
    {
        $factory = new SecurityQuestionFirstInputFilterFactory();
        $this->firstSubject = $factory->createService(Bootstrap::getServiceManager());

        $factory = new SecurityQuestionSecondInputFilterFactory();
        $this->secondSubject = $factory->createService(Bootstrap::getServiceManager());
    }

    public function testInputFilterFactory()
    {
        $this->assertContainsOnlyInstancesOf(
            SecurityQuestionAbstractInputFilter::class,
            [
                $this->firstSubject,
                $this->secondSubject,
            ]
        );
    }

    /**
     * @param string[] $data Represent input fields name and value
     * @param boolean $isValid Expected state
     * @param array $messages Nested array of field names and related messages
     * @dataProvider dpDataAndExpectedResults
     */
    public function testValidators($data, $isValid, $errorMessages)
    {
        $testSubjects = [
            self::STEP_FIRST => $this->firstSubject,
            self::STEP_SECOND => $this->secondSubject
        ];

        foreach ($testSubjects as $step => $subject) {

            $this->targetStep = $step;

            $subject->setData($data);

            $this->assertSame(
                $isValid,
                $subject->isValid(),
                sprintf(
                    'Failed asserting isValid method on SecurityQuestion%sInputFilter returns %s.',
                    ucfirst($step),
                    var_export($isValid, true)
                )
            );

            $this->assertEquals(
                $errorMessages,
                $subject->getMessages(),
                sprintf(
                    'Failed asserting validation message on SecurityQuestion%sInputFilter.',
                    ucfirst($step)
                )
            );
        }
    }

    public function dpDataAndExpectedResults()
    {
        $data = [
            [
                'data' => $this->prepareDataForStep(
                    1,
                    'answer'
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessagesForStep(
                    [],
                    []
                ),
            ],
            [
                'data' => $this->prepareDataForStep(
                    '',
                    ''
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessagesForStep(
                    [
                        NotEmpty::IS_EMPTY => SecurityQuestionAbstractInputFilter::MSG_QUESTION_EMPTY,
                        Digits::STRING_EMPTY => SecurityQuestionAbstractInputFilter::MSG_QUESTION_NOT_NUMERIC,
                    ],
                    [
                        NotEmpty::IS_EMPTY => SecurityQuestionAbstractInputFilter::MSG_ANSWER_EMPTY,
                    ]
                ),
            ],
            [
                'data' => $this->prepareDataForStep(
                    1,
                    str_repeat('a', SecurityQuestionAbstractInputFilter::LIMIT_ANSWER_MAX + 1)
                ),
                'isValid' => false,
                'errorMessages' => $this->prepareMessagesForStep(
                    [],
                    [
                        StringLength::TOO_LONG =>
                            sprintf(
                                SecurityQuestionAbstractInputFilter::MSG_ANSWER_MAX,
                                SecurityQuestionAbstractInputFilter::LIMIT_ANSWER_MAX
                            )
                    ]
                ),
            ],
            [
                'data' => $this->prepareDataForStep(
                    1,
                    str_repeat('a', SecurityQuestionAbstractInputFilter::LIMIT_ANSWER_MAX)
                ),
                'isValid' => true,
                'errorMessages' => $this->prepareMessagesForStep(
                    [],
                    []
                ),
            ],
        ];

        return $data;
    }

    /**
     * @param $question
     * @param $answer
     * @return array
     */
    public function prepareDataForStep(
        $question,
        $answer
    ) {

        if (self::STEP_FIRST == $this->targetStep) {
            $data = [
                SecurityQuestionFirstInputFilter::FIELD_QUESTION => $question,
                SecurityQuestionFirstInputFilter::FIELD_ANSWER => $answer,
            ];
        } elseif (self::STEP_SECOND == $this->targetStep) {
            $data = [
                SecurityQuestionSecondInputFilter::FIELD_QUESTION => $question,
                SecurityQuestionSecondInputFilter::FIELD_ANSWER => $answer,
            ];
        } else {
            throw new \OutOfRangeException(
                sprintf('%s is not acceptable, try %s or %s', $this->targetStep, self::STEP_FIRST, self::STEP_SECOND)
            );
        }

        return $data;
    }

    /**
     * @param array $questionMessages
     * @param array $answerMessages
     * @return array
     */
    public function prepareMessagesForStep(
        $questionMessages,
        $answerMessages
    ) {
        $messages = [];

        if (self::STEP_FIRST == $this->targetStep) {
            if (!empty($questionMessages)) {
                $messages[SecurityQuestionFirstInputFilter::FIELD_QUESTION] = $questionMessages;
            }
            if (!empty($answerMessages)) {
                $messages[SecurityQuestionFirstInputFilter::FIELD_ANSWER] = $answerMessages;
            }
        } elseif (self::STEP_SECOND == $this->targetStep) {
            if (!empty($questionMessages)) {
                $messages[SecurityQuestionSecondInputFilter::FIELD_QUESTION] = $questionMessages;
            }
            if (!empty($answerMessages)) {
                $messages[SecurityQuestionSecondInputFilter::FIELD_ANSWER] = $answerMessages;
            }
        } else {
            throw new \OutOfRangeException(
                sprintf('%s is not acceptable, try %s or %s', $this->targetStep, self::STEP_FIRST, self::STEP_SECOND)
            );
        }

        return $messages;
    }
}
