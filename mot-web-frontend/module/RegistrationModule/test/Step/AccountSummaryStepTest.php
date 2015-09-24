<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Step;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\AccountSummaryStep;
use DvsaCommonTest\TestUtils\XMock;
use Zend\InputFilter\InputFilter;

/**
 * Class AccountSummaryStepTest.
 *
 * @group VM-115061
 */
class AccountSummaryStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the constructor.
     *
     * @throws \Exception
     */
    public function testConstructor()
    {
        $step = new AccountSummaryStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertInstanceOf(AccountSummaryStep::class, $step);
    }

    public function testId()
    {
        $step = new AccountSummaryStep(
            XMock::of(RegistrationSessionService::class),
            XMock::of(InputFilter::class)
        );

        $this->assertEquals(AccountSummaryStep::STEP_ID, $step->getId());
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

        $step = new AccountSummaryStep(
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
        $this->markTestSkipped('Skipped, We are moving the private functions to a helper class, that will be tested.');
        $session = XMock::of(RegistrationSessionService::class);
        $session->expects($this->once())
            ->method('toArray')
            ->willReturn('');
        $step = new AccountSummaryStep($session);

        $values = $step->toArray();

        $this->assertEquals([], $values);
    }
}
