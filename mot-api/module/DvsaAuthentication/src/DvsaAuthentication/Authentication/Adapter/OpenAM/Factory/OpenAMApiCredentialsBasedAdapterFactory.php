<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiCredentialsBasedAdapter;
use DvsaAuthentication\Factory\IdentityFactoryFactory;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpenAMApiCredentialsBasedAdapterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return \DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiCredentialsBasedAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $openAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $realm               = $openAMClientOptions->getRealm();
        $uuidAttribute       = $openAMClientOptions->getIdentityAttributeUuid();

        /**
         * @var OpenAMClientInterface $openAMClient
         * @var LoggerInterface $logger
         */
        $openAMClient = $serviceLocator->get(OpenAMClientInterface::class);
        $logger = $serviceLocator->get('Application\Logger');

        $adapter = new OpenAMApiCredentialsBasedAdapter(
            $openAMClient,
            $realm,
            $serviceLocator->get(IdentityFactoryFactory::class),
            $logger,
            $uuidAttribute
        );

        return $adapter;
    }
}
