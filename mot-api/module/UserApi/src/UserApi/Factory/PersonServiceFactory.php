<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaEntities\Entity\Person;
use OrganisationApi\Service\Mapper\PersonMapper;
use UserApi\Person\Service\PersonService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PersonService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realm = $serviceLocator->get(OpenAMClientOptions::class)->getRealm();

        return new PersonService(
            $serviceLocator->get(EntityManager::class)->getRepository(Person::class),
            new PersonMapper(),
            $serviceLocator->get(OpenAMClientInterface::class),
            $realm,
            $serviceLocator->get('TesterService'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('DvsaAuthenticationService')
        );
    }
}
