<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\EnforcementFullPartialRetest;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaEntities\Entity\MotTestReasonForCancel;
use DvsaMotApi\Service\MotTestDateHelper;
use DvsaMotApi\Service\MotTestStatusChangeService;
use DvsaMotApi\Service\TestingOutsideOpeningHoursNotificationService;
use DvsaMotApi\Service\Validator\MotTestStatusChangeValidator;
use OrganisationApi\Service\OrganisationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommonApi\Authorisation\Assertion\ApiPerformMotTestAssertion;
use DvsaCommonApi\Filter\XssFilter;

/**
 * Class MotTestStatusChangeServiceFactory.
 */
class MotTestStatusChangeServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \DvsaMotApi\Service\MotTestStatusChangeService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new MotTestStatusChangeService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestValidator'),
            $serviceLocator->get(MotTestStatusChangeValidator::class),
            $serviceLocator->get('OtpService'),
            $serviceLocator->get(OrganisationService::class),
            $serviceLocator->get('MotTestMapper'),
            $serviceLocator->get('MotTestRepository'),
            $entityManager->getRepository(MotTestReasonForCancel::class),
            $entityManager->getRepository(EnforcementFullPartialRetest::class),
            $serviceLocator->get(TestingOutsideOpeningHoursNotificationService::class),
            $serviceLocator->get(MotTestDateHelper::class),
            $entityManager,
            $serviceLocator->get(MotIdentityProviderInterface::class),
            $serviceLocator->get(ApiPerformMotTestAssertion::class),
            $serviceLocator->get(XssFilter::class)
        );
    }
}
