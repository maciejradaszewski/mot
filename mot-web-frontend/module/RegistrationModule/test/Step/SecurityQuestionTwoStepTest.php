<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Step;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\SecurityQuestionTwoStep;
use DvsaCommonTest\TestUtils\XMock;
use Zend\InputFilter\InputFilter;

/**
 * Class SecurityQuestionTwoStepTest.
 *
 * @group VM-11506
 */
class SecurityQuestionTwoStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the constructor.
     *
     * @throws \Exception
     */
    public function testConstructor()
    {
        $step = new SecurityQuestionTwoStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertInstanceOf(SecurityQuestionTwoStep::class, $step);
    }

    /**
     * Placeholder test until validation stories are implemented.
     */
    public function testId()
    {
        $step = new SecurityQuestionTwoStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertEquals(SecurityQuestionTwoStep::STEP_ID, $step->getId());
    }

    /**
     * Test loading data returned from the session.
     *
     * @throws \Exception
     */
    public function testLoad()
    {
        $fixture = $this->getFixture();

        $session = XMock::of(RegistrationSessionService::class);
        $session->expects($this->once())
            ->method('load')
            ->with(SecurityQuestionTwoStep::STEP_ID)
            ->willReturn($fixture);

        $step = new SecurityQuestionTwoStep(
            $session,
            XMock::of(InputFilter::class)
        );

        $step->load();

        $this->assertEquals($step->getQuestion(), $fixture['question2']);
        $this->assertEquals($step->getAnswer(), $fixture['answer2']);
    }

    /**
     * Test extracting values into an array.
     */
    public function testToArray()
    {
        $step = new SecurityQuestionTwoStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $step->setQuestion('question2');
        $step->setAnswer('answer2');

        $values = $step->toArray();

        $this->assertEquals('question2', $values['question2']);
        $this->assertEquals('answer2', $values['answer2']);
    }

    /**
     * Test all the property getters and setters.
     */
    public function testGettersSetters()
    {
        $step = new SecurityQuestionTwoStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $step->setQuestion('question2');
        $step->setAnswer('answer2');

        $this->assertEquals('question2', $step->getQuestion());
        $this->assertEquals('answer2', $step->getAnswer());
    }

    /**
     * @return array
     */
    public function getFixture()
    {
        $fixture = [
            'question2' => __METHOD__ . '_question2',
            'answer2'   => __METHOD__ . '_answer2',
        ];

        return $fixture;
    }
}
