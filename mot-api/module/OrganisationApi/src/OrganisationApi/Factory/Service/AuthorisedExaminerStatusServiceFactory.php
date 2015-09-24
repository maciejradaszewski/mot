<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommonApi\Filter\XssFilter;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\Organisation;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Service\AuthorisedExaminerStatusService;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorisedExaminerServiceFactory.
 */
class AuthorisedExaminerStatusServiceFactory implements FactoryInterface
{
    /**
     * @param  \Zend\ServiceManager\ServiceLocatorInterface       $serviceLocator
     * @return \OrganisationApi\Service\AuthorisedExaminerService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new AuthorisedExaminerStatusService(
            $entityManager,
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(MotIdentityProviderInterface::class)->getIdentity(),
            $serviceLocator->get(EventService::class),
            $entityManager->getRepository(Organisation::class),
            $entityManager->getRepository(AuthForAeStatus::class),
            $serviceLocator->get(XssFilter::class),
            new AuthorisedExaminerValidator(),
            new DateTimeHolder()
        );
    }
}
