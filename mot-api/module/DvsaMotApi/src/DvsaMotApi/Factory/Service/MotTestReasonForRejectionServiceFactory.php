<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\MotTestReasonForRejectionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommonApi\Authorisation\Assertion\ApiPerformMotTestAssertion;

/**
 * Class MotTestReasonForRejectionServiceFactory
 *
 * @package DvsaMotApi\Factory\Service
 */
class MotTestReasonForRejectionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestReasonForRejectionService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestValidator'),
            $serviceLocator->get('TestItemSelectorService'),
            $serviceLocator->get(ApiPerformMotTestAssertion::class)
        );
    }
}
