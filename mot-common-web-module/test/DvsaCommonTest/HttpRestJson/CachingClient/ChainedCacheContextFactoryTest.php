<?php

namespace DvsaCommonTest\HttpRestJson\CachingClient;

use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;
use DvsaCommon\HttpRestJson\CachingClient\ChainedCacheContextFactory;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

class ChainedCacheContextFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItIsACacheContextFactory()
    {
        $this->assertInstanceOf(CacheContextFactory::class, new ChainedCacheContextFactory([]));
    }

    public function testItReturnsANotCachingContextByDefault()
    {
        $cacheContextFactory = new ChainedCacheContextFactory([]);

        $this->assertEquals(CacheContext::notCached(), $cacheContextFactory->fromResourcePath('foo'));
    }

    public function testItReturnsACacheContextOfTheFirstFactoryInTheChain()
    {
        $cacheContext = CacheContext::configured('foo', 10);

        $factory1 = $this->createAcceptingFactory('foo', $cacheContext);
        $factory2 = $this->createAcceptingFactory('foo', CacheContext::configured('bar', 20));

        $cacheContextFactory = new ChainedCacheContextFactory([$factory1, $factory2]);

        $this->assertEquals($cacheContext, $cacheContextFactory->fromResourcePath('foo'));
    }

    public function testItReturnsANotCachingContextIfNoneOfFactoriesSupportsTheResourcePath()
    {
        $factory1 = $this->createNonAcceptingFactory();
        $factory2 = $this->createNonAcceptingFactory();

        $cacheContextFactory = new ChainedCacheContextFactory([$factory1, $factory2]);

        $this->assertEquals(CacheContext::notCached(), $cacheContextFactory->fromResourcePath('foo'));
    }

    public function testItReturnsACacheContextOfTheFirstFactoryInTheChainThatSupportsTheResourcePath()
    {
        $cacheContext = CacheContext::configured('foo', 10);

        $factory1 = $this->createNonAcceptingFactory();
        $factory2 = $this->createAcceptingFactory('foo', $cacheContext);

        $cacheContextFactory = new ChainedCacheContextFactory([$factory1, $factory2]);

        $this->assertEquals($cacheContext, $cacheContextFactory->fromResourcePath('foo'));
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testItOnlyAcceptsConditionalContextFactories()
    {
        $factory = $this->getMock(CacheContextFactory::class);

        new ChainedCacheContextFactory([$factory]);
    }

    private function createAcceptingFactory($resourcePath, CacheContext $cacheContext)
    {
        $factory = $this->getMock(ConditionalCacheContextFactory::class);

        $factory->expects($this->any())
            ->method('accepts')
            ->willReturn(true);

        $factory->expects($this->any())
            ->method('fromResourcePath')
            ->with($resourcePath)
            ->willReturn($cacheContext);

        return $factory;
    }

    private function createNonAcceptingFactory()
    {
        $factory = $this->getMock(ConditionalCacheContextFactory::class);

        $factory->expects($this->any())
            ->method('accepts')
            ->willReturn(false);

        return $factory;
    }
}