<?php

namespace DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

/**
 * It is safe to cache Organisation Name as it does not change too often, if at all
 */
class OrganisationNameCacheContextFactory implements ConditionalCacheContextFactory
{
    const DEFAULT_LIFE_TIME = 300;
    const PATTERN = '#vehicle-testing-station/(?P<siteId>\d+)/(organisation/name)#';

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
                []
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
