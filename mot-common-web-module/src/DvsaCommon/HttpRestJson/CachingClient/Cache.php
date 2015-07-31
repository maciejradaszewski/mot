<?php

namespace DvsaCommon\HttpRestJson\CachingClient;

use Doctrine\Common\Cache\Cache as CacheBackend;

class Cache
{
    /**
     * @var CacheBackend
     */
    private $cacheBackend;

    /**
     * @param CacheBackend $cacheBackend
     */
    public function __construct(CacheBackend $cacheBackend)
    {
        $this->cacheBackend = $cacheBackend;
    }

    /**
     * @param CacheContext $cacheContext
     *
     * @return mixed
     */
    public function fetch(CacheContext $cacheContext)
    {
        $value = $this->cacheBackend->fetch($cacheContext->getKey());

        if (null !== $value) {
            $value = unserialize($value);
        }

        return $value;
    }

    /**
     * @param CacheContext $cacheContext
     * @param mixed        $value
     *
     * @return null
     */
    public function store(CacheContext $cacheContext, $value)
    {
        if (null !== $key = $cacheContext->getKey()) {
            $this->cacheBackend->save($key, serialize($value), $cacheContext->getLifeTime());
        }
    }

    /**
     * @param CacheContext $cacheContext
     *
     * @return null
     */
    public function invalidate(CacheContext $cacheContext)
    {
        foreach ($cacheContext->getInvalidationKeys() as $key) {
            $this->cacheBackend->delete($key);
        }
    }
}