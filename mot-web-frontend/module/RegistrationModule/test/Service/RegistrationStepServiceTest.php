<?php
namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\RegistrationStep;
use DvsaCommonTest\TestUtils\XMock;

/**
 * Class RegistrationStepServiceTest.
 *
 * @group registration
 */
class RegistrationStepServiceTest extends \PHPUnit_Framework_TestCase
{
    const FIRST = 'first';

    /**
     * @var RegistrationStepService
     */
    private $registrationStepService;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject[]
     */
    private $stepInterfaceStackMock;

    /**
     * @var array
     */
    private $data = [
        self::FIRST,
        'second',
        'third',
    ];

    /**
     * Setup function for RegistrationService.
     */
    public function setUp()
    {
        $this->registrationStepService = new RegistrationStepService();

        foreach ($this->data as $value) {
            $mock = $this->createStepMock($value);
            $this->stepInterfaceStackMock[] = $mock;

            $this->registrationStepService->add($mock);
        }
    }

    public function testConstructor()
    {
        $registrationStepService = new RegistrationStepService($this->stepInterfaceStackMock);
        $this->assertInstanceOf(RegistrationStepService::class, $registrationStepService);

        $expected = current($this->stepInterfaceStackMock);
        $actual = $registrationStepService->current();

        $this->assertSame($expected, $actual);
    }

    public function testNext()
    {
        // Testing to see if the pointer has been incremented by next
        $expected = next($this->stepInterfaceStackMock);

        $actual = $this->registrationStepService->next();
        $this->assertInstanceOf(RegistrationStep::class, $actual);
        $this->assertSame($expected, $actual);
    }

    public function testNext_False()
    {
        $this->registrationStepService->next();
        $this->registrationStepService->next();
        $actual = $this->registrationStepService->next();
        $this->assertFalse($actual);
    }

    public function testPrevious()
    {
        $this->registrationStepService->next();
        $actual = $this->registrationStepService->previous();
        $expected = $this->stepInterfaceStackMock[0];
        $this->assertInstanceOf(RegistrationStep::class, $actual);
        $this->assertSame($expected, $actual);
    }

    public function testPrevious_False()
    {
        $this->assertFalse($this->registrationStepService->previous());
    }

    public function testCurrent()
    {
        $actual = $this->registrationStepService->current();
        $expected = current($this->stepInterfaceStackMock);

        $this->assertSame($expected, $actual);
    }

    public function testRewind()
    {
        $this->registrationStepService->rewind();
        $actualClass = $this->registrationStepService->current();

        $this->assertSame($this->stepInterfaceStackMock[0], $actualClass);
    }

    public function testGetById()
    {
        $actual = $this->registrationStepService->getById(self::FIRST);
        $expected = $this->stepInterfaceStackMock[0];

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetById_Exception()
    {
        $this->registrationStepService->getById('doesNotExist');
    }

    public function testAdd()
    {
        $mockStep = $this->createStepMock(__METHOD__);
        $actual = $this->registrationStepService->add($mockStep);

        $expected = 3;

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException Exception
     */
    public function testAdd_Exception()
    {
        $mockStep = $this->createStepMock(self::FIRST);
        $this->registrationStepService->add($mockStep);
    }

    public function testFirst()
    {
        $actual = $this->registrationStepService->first();
        $expected = $this->stepInterfaceStackMock[0];
        $this->assertInstanceOf(RegistrationStep::class, $actual);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests first when no steps have been added.
     *
     * @expectedException Exception
     */
    public function testFirst_Exception()
    {
        $registrationStepService = new RegistrationStepService();
        $registrationStepService->first();
    }

    public function testLast()
    {
        $actual = $this->registrationStepService->last();
        $index = count($this->stepInterfaceStackMock) - 1;
        $expected = $this->stepInterfaceStackMock[$index];
        $this->assertInstanceOf(RegistrationStep::class, $actual);
        $this->assertSame($expected, $actual);
    }

    public function testIsLast()
    {
        $this->assertFalse($this->registrationStepService->isLast());
        $this->registrationStepService->next();
        $this->registrationStepService->next();
        $this->assertTrue($this->registrationStepService->isLast());
    }

    public function testSetActiveById()
    {
        $this->registrationStepService->next();
        $this->registrationStepService->setActiveById(self::FIRST);

        $actual = $this->registrationStepService->current();
        $expected = $this->registrationStepService->getById(self::FIRST);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException Exception
     */
    public function testSetActiveById_Exception()
    {
        $this->registrationStepService->setActiveById("notAStep");
    }

    public function testSetActiveStepByRegistrationStep()
    {
        $this->registrationStepService->next();

        $registrationStepMock = $this->createStepMock(self::FIRST);

        $this->registrationStepService->setActiveByRegistrationStep($registrationStepMock);

        $actual = $this->registrationStepService->current();
        $expected = $this->registrationStepService->getById(self::FIRST);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException Exception
     */
    public function testSetActiveByRegistrationStep_Exception()
    {
        $registrationStepMock = $this->createStepMock('iDoNotExist');
        $this->registrationStepService->setActiveByRegistrationStep($registrationStepMock);
    }

    public function testCount()
    {
        $numberOfSteps = $this->registrationStepService->count();
        $this->assertSame(3, $numberOfSteps);
    }

    public function testGetPosition()
    {
        $this->registrationStepService->next();
        $currentStepNumber = $this->registrationStepService->getPosition();
        $this->assertSame(2, $currentStepNumber);
    }

    /**
     * Tests current when no steps have been added.
     *
     * @expectedException Exception
     */
    public function testCurrent_Exception()
    {
        $registrationStepService = new RegistrationStepService();
        $registrationStepService->current();
    }

    public function testGetIterator()
    {
        $count = 0;
        foreach ($this->registrationStepService as $step) {
            $expected = $this->stepInterfaceStackMock[$count];
            $this->assertSame($expected, $step);
            $count++;
        }
    }

    /**
     * @param string $value
     *
     * @throws \Exception
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createStepMock($value)
    {
        $mock = XMock::of(RegistrationStep::class, ['getId']);
        $mock->expects($this->any())
            ->method('getId')
            ->willReturn($value);

        return $mock;
    }
}
