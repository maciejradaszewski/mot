<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Step;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CreateAccountStep;
use DvsaCommonTest\TestUtils\XMock;
use Zend\InputFilter\InputFilter;

/**
 * Class CreateAccountStepTest.
 *
 * @group VM-11506
 */
class CreateAccountStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the constructor.
     *
     * @throws \Exception
     */
    public function testConstructor()
    {
        $step = new CreateAccountStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertInstanceOf(CreateAccountStep::class, $step);
    }

    /**
     * Placeholder test until validation stories are implemented.
     */
    public function testId()
    {
        $step = new CreateAccountStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertEquals(CreateAccountStep::STEP_ID, $step->getId());
    }

    /**
     * Test loading data returned from the session
     *  - There are currently no values in this step, so no assertions required.
     *  - load is implemented to conform with StepInterface.
     *
     * @throws \Exception
     */
    public function testLoad()
    {
        $session = XMock::of(RegistrationSessionService::class);

        $session->expects($this->never())
            ->method('load');

        $step = new CreateAccountStep(
            $session,
            XMock::of(InputFilter::class)
        );

        $step->load();
    }

    /**
     * Test extracting values into an array
     *  - There are currently no getters and setters on this step, so no
     *    values should be returned.
     */
    public function testToArray()
    {
        $step = new CreateAccountStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $values = $step->toArray();

        $this->assertEquals([], $values);
    }
}
