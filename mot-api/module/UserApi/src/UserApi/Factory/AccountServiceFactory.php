<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommon\Auth\Assertion\CreateUserAccountAssertion;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaEntities\Entity\Gender;
use DvsaEntities\Entity\Title;
use DvsaEntities\Mapper\PersonMapper as CommonEntitiesPersonMapper;
use UserApi\Application\Service\AccountService;
use UserApi\Application\Service\Validator\AccountValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AccountServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AccountService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        $realm = $serviceLocator->get(OpenAMClientOptions::class)->getRealm();
        $openAMClient = $serviceLocator->get(OpenAMClientInterface::class);
        $createUserAccountAssertion = new CreateUserAccountAssertion($serviceLocator->get('DvsaAuthorisationService'));
        $xssFilter = $serviceLocator->get(XssFilter::class);

        return new AccountService(
            $entityManager,
            $serviceLocator->get(AccountValidator::class),
            $serviceLocator->get(ContactDetailsService::class),
            new CommonEntitiesPersonMapper(
                $entityManager->getRepository(Title::class),
                $entityManager->getRepository(Gender::class)
            ),
            $createUserAccountAssertion,
            $openAMClient,
            $realm,
            $xssFilter
        );
    }
}
