<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMCachedClient;
use DvsaAuthentication\IdentityFactory\CacheableIdentityFactory;
use DvsaAuthentication\IdentityFactory\DoctrineIdentityFactory;
use DvsaCommon\Configuration\MotConfig;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;
use DvsaAuthentication\Factory\IdentityFactoryFactory;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class OpenAMApiTokenBasedAdapterFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $serviceLocator
     *
     * @return OpenAMApiTokenBasedAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $openAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $usernameAttribute   = $openAMClientOptions->getIdentityAttributeUsername();
        $uuidAttribute       = $openAMClientOptions->getIdentityAttributeUuid();

        /**
         * @var OpenAMClientInterface $openAMClient
         * @var TokenServiceInterface $tokenService
         * @var LoggerInterface $logger
         */
        $openAMClient = $this->getOpenAMClient($serviceLocator);
        $logger = $serviceLocator->get('Application\Logger');
        $tokenService = $serviceLocator->get('tokenService');

        $adapter = new OpenAMApiTokenBasedAdapter(
            $openAMClient,
            $usernameAttribute,
            $serviceLocator->get(IdentityFactoryFactory::class),
            $logger,
            $tokenService,
            $uuidAttribute
        );

        return $adapter;
    }

    private function getOpenAMClient(ServiceLocatorInterface $serviceLocator)
    {
        $openAMClientClass = $this->isCacheEnabled($serviceLocator)
            ? OpenAMCachedClient::class
            : OpenAMClientInterface::class;

        return $serviceLocator->get($openAMClientClass);
    }

    private function isCacheEnabled(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MotConfig $config */
        $config = $serviceLocator->get(MotConfig::class);

        return $config->withDefault(false)->get('cache', 'open_am_client', 'enabled');
    }
}
