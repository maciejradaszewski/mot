<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\Common\Cache\Cache;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMCachedClient;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMIdentityAttributesCacheProvider;
use DvsaCommon\Configuration\MotConfig;
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
        /** @var MotConfig $config */
        $config = $serviceLocator->get(MotConfig::class);

        return $config->withDefault(15)->get('cache', 'open_am_client', 'ttl');
    }
}
