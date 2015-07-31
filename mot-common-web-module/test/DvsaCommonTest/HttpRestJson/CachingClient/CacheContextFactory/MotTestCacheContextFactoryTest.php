<?php

namespace DvsaCommonTest\HttpRestJson\CachingClient\CacheContextFactory;

use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory\MotTestCacheContextFactory;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

class MotTestCacheContextFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConditionalCacheContextFactory
     */
    private $cacheContextFactory;

    /**
     * @var int
     */
    private $lifeTime = 60;

    public function setUp()
    {
        $this->cacheContextFactory = new MotTestCacheContextFactory($this->lifeTime);
    }

    public function testItIsAConditionalCacheContextFactory()
    {
        $this->assertInstanceOf(ConditionalCacheContextFactory::class, $this->cacheContextFactory);
    }

    public function testItAcceptsMotTestResourcePaths()
    {
        $this->assertTrue($this->cacheContextFactory->accepts('mot-test'));
    }

    public function testItDoesNotAcceptOtherResourcePaths()
    {
        $this->assertFalse($this->cacheContextFactory->accepts('not-an-mot-test'));
    }

    /**
     * @dataProvider provideCacheContextCases
     */
    public function testItCreatesACacheContext($resourcePath, $expectedCacheContext)
    {
        $this->assertEquals($expectedCacheContext, $this->cacheContextFactory->fromResourcePath($resourcePath));
    }

    public function provideCacheContextCases()
    {
        return [
            [
                'foo',
                CacheContext::notCached(),
                'It creates a not cached context by default'
            ],
            [
                'mot-test/1234',
                CacheContext::configured('mot-test/1234', $this->lifeTime, ['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures the mot test cache'
            ],
            [
                'mot-test/1234/minimal',
                CacheContext::configured('mot-test/1234/minimal', $this->lifeTime, ['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures the mot test minimal cache'
            ],
            [
                'mot-test/1234/brake-test-result',
                CacheContext::notCached(['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures invalidation keys for brake test results'
            ],
            [
                'mot-test/1234/odometer-reading',
                CacheContext::notCached(['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures invalidation keys for the odometer reading'
            ],
            [
                'mot-test/1234/odometer-reading/notices',
                CacheContext::configured('mot-test/1234/odometer-reading/notices', $this->lifeTime, ['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures the odometer reading notices'
            ],
            [
                'mot-test/1234/reasons-for-rejection',
                CacheContext::notCached(['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures invalidation keys for reasons for rejection'
            ],
            [
                'mot-test/1234/reasons-for-rejection/18',
                CacheContext::notCached(['mot-test/1234', 'mot-test/1234/minimal', 'mot-test/1234/odometer-reading/notices']),
                'It configures invalidation keys for reasons for rejection removal'
            ],
            [
                'mot-test/1234/some-random-resource',
                CacheContext::notCached(),
                'It does not configure random MOT test resources'
            ],
            [
                'mot-test/359107533408/status',
                CacheContext::notCached(['mot-test/359107533408', 'mot-test/359107533408/minimal', 'mot-test/359107533408/odometer-reading/notices']),
                'It configures invalidation keys for mot-test status update'
            ],
            [
                'mot-test/359107533408/replacement-certificate-draft',
                CacheContext::notCached(['mot-test/359107533408', 'mot-test/359107533408/minimal', 'mot-test/359107533408/odometer-reading/notices']),
                'It configures invalidation keys for the replacement certificate update'
            ],
        ];
    }
}