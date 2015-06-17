<?php

namespace DvsaAuthentication\Authentication\Adapter\OpenAM\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;
use Zend\Http\PhpEnvironment\Request;
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
        $openAMClient = $serviceLocator->get(OpenAMClientInterface::class);
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
}
