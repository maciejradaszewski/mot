<?php

namespace DvsaCommon\HttpRestJson\CachingClient;

interface CacheContextFactory
{
    /**
     * @param string $resourcePath
     *
     * @return CacheContext
     */
    public function fromResourcePath($resourcePath);
}