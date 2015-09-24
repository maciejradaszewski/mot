<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\Common\Cache\Cache;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\OpenAMClient;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMCachedClient;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMIdentityAttributesCacheProvider;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpenAMCachedClientFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var OpenAMClientInterface $client */
        $client = $serviceLocator->get(OpenAMClientInterface::class);

        /** @var Cache $cache */
        $cache = $serviceLocator->get(Cache::class);

        $timeToLive = $this->getCacheTimeToLive($serviceLocator);

        /** @var LoggerInterface $logger */
        $logger = $serviceLocator->get('Application\Logger');

        $cacheProvider = new OpenAMIdentityAttributesCacheProvider($cache, $timeToLive);

        return new OpenAMCachedClient($client, $cacheProvider, $logger);
    }

    private function getCacheTimeToLive(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        $timeToLive = 15;

        if (isset($config['cache'])
        && isset($config['cache']['open_am_client'])
        && isset($config['cache']['open_am_client']['ttl'])){
            $timeToLive = $config['cache']['open_am_client']['ttl'];
        }

        return $timeToLive;
    }
}
