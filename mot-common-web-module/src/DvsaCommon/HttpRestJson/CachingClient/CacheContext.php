<?php

namespace DvsaCommon\HttpRestJson\CachingClient;

class CacheContext
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * @var int
     */
    private $lifeTime = 0;

    /**
     * @var string[]
     */
    private $invalidationKeys = [];

    private function __construct()
    {
    }

    /**
     * @param string[] $invalidationKeys
     *
     * @return CacheContext
     */
    public static function notCached(array $invalidationKeys = [])
    {
        $cacheContext = new self();
        $cacheContext->invalidationKeys = $invalidationKeys;

        return $cacheContext;
    }

    /**
     * @param string $key
     * @param int    $lifeTime
     * @param array  $invalidationKeys
     *
     * @return CacheContext
     */
    public static function configured($key, $lifeTime, array $invalidationKeys = [])
    {
        $context = new self();
        $context->key = $key;
        $context->lifeTime = $lifeTime;
        $context->invalidationKeys = $invalidationKeys;

        return $context;
    }

    /**
     * @return string|null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return integer the number of seconds the cache should be valid for
     */
    public function getLifeTime()
    {
        return $this->lifeTime;
    }

    /**
     * @return string[]
     */
    public function getInvalidationKeys()
    {
        return $this->invalidationKeys;
    }
}