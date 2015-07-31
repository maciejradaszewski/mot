<?php

namespace DvsaCommon\HttpRestJson\CachingClient;

class ChainedCacheContextFactory implements CacheContextFactory
{
    /**
     * @var ConditionalCacheContextFactory[]
     */
    private $factories = [];

    /**
     * @param ConditionalCacheContextFactory[] $factories
     */
    public function __construct(array $factories)
    {
        foreach ($factories as $factory) {
            $this->addFactory($factory);
        }
    }

    /**
     * @param string $resourcePath
     *
     * @return CacheContext
     */
    public function fromResourcePath($resourcePath)
    {
        foreach ($this->factories as $factory) {
            if ($factory->accepts($resourcePath)) {
                return $factory->fromResourcePath($resourcePath);
            }
        }

        return CacheContext::notCached();
    }

    /**
     * @param ConditionalCacheContextFactory $factory
     */
    private function addFactory(ConditionalCacheContextFactory $factory)
    {
        $this->factories[] = $factory;
    }
}