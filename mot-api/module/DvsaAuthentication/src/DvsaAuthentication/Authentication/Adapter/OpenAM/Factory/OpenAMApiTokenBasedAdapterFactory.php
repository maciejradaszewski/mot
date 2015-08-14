<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMCachedClient;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;
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
         * @var EntityManager $entityManager
         * @var PersonRepository $personRepository
         * @var OpenAMClientInterface $openAMClient
         * @var TokenServiceInterface $tokenService
         * @var LoggerInterface $logger
         */
        $openAMClient = $this->getOpenAMClient($serviceLocator);
        $entityManager = $serviceLocator->get(EntityManager::class);
        $personRepository = $entityManager->getRepository(Person::class);
        $logger = $serviceLocator->get('Application\Logger');

        $tokenService = $serviceLocator->get('tokenService');
        $adapter = new OpenAMApiTokenBasedAdapter(
            $openAMClient,
            $usernameAttribute,
            $personRepository,
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
        $openAMCacheEnabled = false;
        $config = $serviceLocator->get('config');
        if (isset($config['cache'])
            && isset($config['cache']['open_am_client'])
            && isset($config['cache']['open_am_client']['enabled'])
        ) {
            $openAMCacheEnabled = true;
        }

        return $openAMCacheEnabled;
    }
}
