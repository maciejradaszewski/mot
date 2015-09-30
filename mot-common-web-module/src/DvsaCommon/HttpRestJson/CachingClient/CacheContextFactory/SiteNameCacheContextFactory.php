<?php

namespace DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

class SiteNameCacheContextFactory implements ConditionalCacheContextFactory
{
    const DEFAULT_LIFE_TIME = 300;
    const PATTERN = '#vehicle-testing-station/(?P<siteId>\d+)/(name)#';

    private $ttl;

    public function __construct($ttl = self::DEFAULT_LIFE_TIME)
    {
        $this->ttl = (int)$ttl;
    }

    /**
     * @param string $resourcePath
     *
     * @return CacheContext
     */
    public function fromResourcePath($resourcePath)
    {
        if (preg_match(self::PATTERN, $resourcePath, $matches)) {
            return CacheContext::configured(
                $resourcePath,
                $this->ttl,
                [
                    sprintf('vehicle-testing-station/%d/name', $matches['siteId']),
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
        return preg_match(self::PATTERN, $resourcePath);
    }
}
