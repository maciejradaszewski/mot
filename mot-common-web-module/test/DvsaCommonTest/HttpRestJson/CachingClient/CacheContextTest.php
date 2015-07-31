<?php

namespace DvsaCommonTest\HttpRestJson\CachingClient;

use DvsaCommon\HttpRestJson\CachingClient\CacheContext;

class CacheContextTest extends \PHPUnit_Framework_TestCase
{
    public function testItCreatesANonCachedContext()
    {
        $context = CacheContext::notCached();

        $this->assertNull($context->getKey());
        $this->assertSame(0, $context->getLifeTime());
        $this->assertSame([], $context->getInvalidationKeys());
    }

    public function testItCreatesANonCachedContextWithInvalidationKeys()
    {
        $context = CacheContext::notCached(['mot-test/1234']);

        $this->assertNull($context->getKey());
        $this->assertSame(0, $context->getLifeTime());
        $this->assertSame(['mot-test/1234'], $context->getInvalidationKeys());
    }

    public function testItCreatesConfiguredContext()
    {
        $context = CacheContext::configured('/foo', 3600, ['/bar/13']);

        $this->assertSame('/foo', $context->getKey());
        $this->assertSame(3600, $context->getLifeTime());
        $this->assertSame(['/bar/13'], $context->getInvalidationKeys());
    }
}