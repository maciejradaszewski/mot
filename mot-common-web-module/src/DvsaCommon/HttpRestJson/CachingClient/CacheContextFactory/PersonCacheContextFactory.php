<?php

namespace DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

class PersonCacheContextFactory implements ConditionalCacheContextFactory
{
    const DEFAULT_LIFE_TIME = 300;

    /**
     * @var TokenServiceInterface
     */
    private $tokenService;

    /**
     * @var
     */
    private $ttl;

    public function __construct(TokenServiceInterface $tokenService, $ttl = self::DEFAULT_LIFE_TIME)
    {
        $this->tokenService = $tokenService;
        $this->ttl = (int) $ttl;
    }

    /**
     * @param string $resourcePath
     *
     * @return CacheContext
     */
    public function fromResourcePath($resourcePath)
    {
        if (preg_match('#person/(?P<personId>\d+)/(site-count|mot-testing)#', $resourcePath, $matches)) {
            return CacheContext::configured(
                $this->calculateCacheKey($resourcePath),
                $this->ttl,
                [
                    $this->calculateCacheKey(sprintf('person/%d/site-count', $matches['personId'])),
                    $this->calculateCacheKey(sprintf('person/%d/mot-testing', $matches['personId'])),
                ]
            );
        }

        if (preg_match('#person/(?P<personId>\d+)/demo-test-assessment#', $resourcePath, $matches)) {
            return CacheContext::notCached(
                [
                    $this->calculateCacheKey(sprintf('person/%d/site-count', $matches['personId'])),
                    $this->calculateCacheKey(sprintf('person/%d/mot-testing', $matches['personId'])),
                ]
            );
        }

        return CacheContext::notCached([]);
    }

    /**
     * @param string $resourcePath
     *
     * @return bool
     */
    public function accepts($resourcePath)
    {
        return 0 === strpos($resourcePath, 'person/');
    }

    /**
     * @param string $resourcePath
     *
     * @return string
     */
    private function calculateCacheKey($resourcePath)
    {
        return sha1($this->tokenService->getToken()) . '_' . $resourcePath;
    }
}