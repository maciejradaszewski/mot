<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModuleTest\View;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGeneratorViewHelper;
use ReflectionObject;

class PersonProfileUrlGeneratorViewHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContextProvider
     */
    private $contextProvider;

    /**
     * @var PersonProfileUrlGenerator
     */
    private $personProfileUrlGenerator;

    /**
     * @var PersonProfileUrlGeneratorViewHelper
     */
    private $personProfileUrlGeneratorViewHelper;

    public function setUp()
    {
        $this
            ->personProfileUrlGenerator = $this->getMockBuilder(PersonProfileUrlGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this
            ->contextProvider = $this->getMockBuilder(ContextProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->personProfileUrlGeneratorViewHelper = new PersonProfileUrlGeneratorViewHelper(
            $this->personProfileUrlGenerator, $this->contextProvider);
    }

    public function testInvokeIsImplemented()
    {
        $r = new ReflectionObject($this->personProfileUrlGeneratorViewHelper);
        $this->assertTrue($r->hasMethod('__invoke'));
    }

    public function testGetContext()
    {
        $context = 'SOME CONTEXT';

        $this
            ->contextProvider
            ->expects($this->once())
            ->method('getContext')
            ->willReturn($context);

        $this->assertEquals($context, $this->personProfileUrlGeneratorViewHelper->getContext());
    }

    public function testGetContextViaInvoke()
    {
        $context = 'SOME CONTEXT';

        $this
            ->contextProvider
            ->expects($this->once())
            ->method('getContext')
            ->willReturn($context);

        $personProfileUrlGeneratorViewHelper = $this->personProfileUrlGeneratorViewHelper;
        $this->assertEquals($context, $personProfileUrlGeneratorViewHelper()->getContext());
    }

    public function testToPersonProfile()
    {
        $url = '/some-url';

        $this
            ->personProfileUrlGenerator
            ->expects($this->once())
            ->method('toPersonProfile')
            ->willReturn($url);

        $this->assertEquals($url, $this->personProfileUrlGeneratorViewHelper->toPersonProfile());
    }

    public function testToPersonProfileViaInvoke()
    {
        $url = '/some-url';

        $this
            ->personProfileUrlGenerator
            ->expects($this->once())
            ->method('toPersonProfile')
            ->willReturn($url);

        $personProfileUrlGeneratorViewHelper = $this->personProfileUrlGeneratorViewHelper;
        $this->assertEquals($url, $personProfileUrlGeneratorViewHelper()->toPersonProfile());
    }

    public function testFromPersonProfile()
    {
        $url = '/some-url';

        $this
            ->personProfileUrlGenerator
            ->expects($this->once())
            ->method('fromPersonProfile')
            ->willReturn($url);

        $this->assertEquals($url, $this->personProfileUrlGeneratorViewHelper->fromPersonProfile('subRoute'));
    }

    public function testFromPersonProfileViaInvoke()
    {
        $url = '/some-url';

        $this
            ->personProfileUrlGenerator
            ->expects($this->once())
            ->method('fromPersonProfile')
            ->willReturn($url);

        $personProfileUrlGeneratorViewHelper = $this->personProfileUrlGeneratorViewHelper;
        $this->assertEquals($url, $personProfileUrlGeneratorViewHelper()->fromPersonProfile('subRoute'));
    }
}
