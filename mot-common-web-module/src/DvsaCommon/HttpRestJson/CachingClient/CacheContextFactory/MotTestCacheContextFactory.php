<?php

namespace DvsaCommon\HttpRestJson\CachingClient\CacheContextFactory;

use DvsaCommon\HttpRestJson\CachingClient\CacheContext;
use DvsaCommon\HttpRestJson\CachingClient\ConditionalCacheContextFactory;

class MotTestCacheContextFactory implements ConditionalCacheContextFactory
{
    const DEFAULT_LIFE_TIME = 7200;

    /**
     * @var int
     */
    private $lifeTime;

    /**
     * @param int $lifeTime
     */
    public function __construct($lifeTime = self::DEFAULT_LIFE_TIME)
    {
        $this->lifeTime = (int) $lifeTime;
    }

    /**
     * @param string $resourcePath
     *
     * @return CacheContext
     */
    public function fromResourcePath($resourcePath)
    {
        if (preg_match('#mot-test/(?P<motTestNumber>[^/]*)(/minimal|/odometer-reading/notices|)$#', $resourcePath, $matches)) {
            return CacheContext::configured(
                $resourcePath,
                $this->lifeTime,
                $this->getMotTestInvalidationKeys($matches['motTestNumber'])
            );
        }

        if (preg_match(
            '#mot-test/(?P<motTestNumber>[^/]*)/(brake-test-result|odometer-reading|reasons-for-rejection|status|replacement-certificate-draft)#',
            $resourcePath,
            $matches
        )) {
            return CacheContext::notCached($this->getMotTestInvalidationKeys($matches['motTestNumber']));
        }

        return CacheContext::notCached();
    }

    /**
     * @param string $resourcePath
     *
     * @return bool
     */
    public function accepts($resourcePath)
    {
        return 0 === strpos($resourcePath, 'mot-test');
    }

    /**
     * @param string $motTestNumber
     *
     * @return array
     */
    private function getMotTestInvalidationKeys($motTestNumber)
    {
        return [
            sprintf('mot-test/%s', $motTestNumber),
            sprintf('mot-test/%s/minimal', $motTestNumber),
            sprintf('mot-test/%s/odometer-reading/notices', $motTestNumber),
        ];
    }
}