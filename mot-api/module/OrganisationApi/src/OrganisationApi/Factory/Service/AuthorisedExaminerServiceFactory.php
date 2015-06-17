<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\CompanyType;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContactType;
use DvsaEntities\Entity\OrganisationType;
use DvsaEntities\Entity\Person;
use OrganisationApi\Service\AuthorisedExaminerService;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\OrganisationService;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;
use OrganisationApi\Service\Validator\OrganisationValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorisedExaminerServiceFactory.
 */
class AuthorisedExaminerServiceFactory implements FactoryInterface
{
    /**
     * @param  \Zend\ServiceManager\ServiceLocatorInterface       $serviceLocator
     * @return \OrganisationApi\Service\AuthorisedExaminerService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthorisedExaminerService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(OrganisationService::class),
            $serviceLocator->get(ContactDetailsService::class),
            $serviceLocator->get(EntityManager::class)->getRepository(Organisation::class),
            $serviceLocator->get(EntityManager::class)->getRepository(Person::class),
            $serviceLocator->get(EntityManager::class)->getRepository(OrganisationType::class),
            $serviceLocator->get(EntityManager::class)->getRepository(CompanyType::class),
            $serviceLocator->get(EntityManager::class)->getRepository(OrganisationContactType::class),
            new AuthorisedExaminerValidator(
                new OrganisationValidator(),
                new ContactDetailsValidator(new AddressValidator())
            ),
            new OrganisationMapper(
                $serviceLocator->get(EntityManager::class)->getRepository(OrganisationType::class),
                $serviceLocator->get(EntityManager::class)->getRepository(CompanyType::class)
            ),
            $serviceLocator->get(EntityManager::class)->getRepository(AuthForAeStatus::class),
            $serviceLocator->get(XssFilter::class)
        );
    }
}
