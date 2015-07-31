<?php

namespace DvsaCommon\HttpRestJson\CachingClient;

interface ConditionalCacheContextFactory extends CacheContextFactory
{
    /**
     * @param string $resourcePath
     *
     * @return bool
     */
    public function accepts($resourcePath);
}