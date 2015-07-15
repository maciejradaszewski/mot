<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\CompanyType;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContactType;
use DvsaEntities\Entity\OrganisationType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
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
        $entityManager = $serviceLocator->get(EntityManager::class);
        $organisationType = $entityManager->getRepository(OrganisationType::class);
        $companyType = $entityManager->getRepository(CompanyType::class);

        return new AuthorisedExaminerService(
            $entityManager,
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(OrganisationService::class),
            $serviceLocator->get(ContactDetailsService::class),
            $entityManager->getRepository(Organisation::class),
            $entityManager->getRepository(Person::class),
            $organisationType,
            $companyType,
            $entityManager->getRepository(OrganisationContactType::class),
            new AuthorisedExaminerValidator(
                new OrganisationValidator(),
                new ContactDetailsValidator(new AddressValidator())
            ),
            new OrganisationMapper(
                $organisationType,
                $companyType
            ),
            $entityManager->getRepository(AuthForAeStatus::class),
            $serviceLocator->get(XssFilter::class),
            $entityManager->getRepository(AuthorisationForAuthorisedExaminer::class),
            $entityManager->getRepository(Site::class)
        );
    }
}
