<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Step;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\SecurityQuestionOneStep;
use DvsaCommonTest\TestUtils\XMock;
use Zend\InputFilter\InputFilter;

/**
 * Class SecurityQuestionOneStepTest.
 *
 * @group VM-11506
 */
class SecurityQuestionOneStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the constructor.
     *
     * @throws \Exception
     */
    public function testConstructor()
    {
        $step = new SecurityQuestionOneStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertInstanceOf(SecurityQuestionOneStep::class, $step);
    }

    /**
     * Placeholder test until validation stories are implemented.
     */
    public function testId()
    {
        $step = new SecurityQuestionOneStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertEquals(SecurityQuestionOneStep::STEP_ID, $step->getId());
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
            ->with(SecurityQuestionOneStep::STEP_ID)
            ->willReturn($fixture);

        $step = new SecurityQuestionOneStep(
            $session,
            XMock::of(InputFilter::class)
        );

        $step->load();

        $this->assertEquals($step->getQuestion(), $fixture['question1']);
        $this->assertEquals($step->getAnswer(), $fixture['answer1']);
    }

    /**
     * Test extracting values into an array.
     */
    public function testToArray()
    {
        $step = new SecurityQuestionOneStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $step->setQuestion('question1');
        $step->setAnswer('answer1');

        $values = $step->toArray();

        $this->assertEquals('question1', $values['question1']);
        $this->assertEquals('answer1', $values['answer1']);
    }

    /**
     * Test all the property getters and setters.
     */
    public function testGettersSetters()
    {
        $step = new SecurityQuestionOneStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $step->setQuestion('question1');
        $step->setAnswer('answer1');

        $this->assertEquals('question1', $step->getQuestion());
        $this->assertEquals('answer1', $step->getAnswer());
    }

    /**
     * @return array
     */
    public function getFixture()
    {
        $fixture = [
            'question1'          => __METHOD__ . '_question1',
            'answer1'            => __METHOD__ . '_answer1',
        ];

        return $fixture;
    }
}
