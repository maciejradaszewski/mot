<?php

namespace ApplicationTest\Navigation\Breadcrumbs\Handler;

use Application\Navigation\Breadcrumbs\Handler\SimpleResolver;
use DvsaCommonTest\TestUtils\XMock;
use Zend\View\Helper\Url;

class SimpleResolverTest extends \PHPUnit_Framework_TestCase
{
    private $urlHelper;
    private $resolver;

    public function setUp()
    {
        $this->urlHelper = XMock::of(Url::class);
        $this->resolver = new SimpleResolver($this->urlHelper);
    }

    public function testResolve_whenLinkSpecified_shouldBuildRoute()
    {
        $this->urlHelper->expects($this->once())->method('__invoke')->with('route1', ['param1', 'param2']);
        $this->resolver->resolve(
            ['label' => 'label1', 'link' => ['route' => 'route1', 'params' => ['param1', 'param2']]]
        );
    }

    public function testResolve_whenNoLinkSpecified_shouldOmitRoute()
    {
        $this->urlHelper->expects($this->never())->method('__invoke');
        $this->resolver->resolve(
            ['label' => 'label1']
        );
    }
}
