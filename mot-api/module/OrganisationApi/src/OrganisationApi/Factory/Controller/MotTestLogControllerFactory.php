<?php

namespace OrganisationApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaElasticSearch\Service\ElasticSearchService;
use OrganisationApi\Controller\MotTestLogController;
use OrganisationApi\Service\MotTestLogService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestLogControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MotTestLogController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator    = $controllerManager->getServiceLocator();

        /** @var MotTestLogService $motTestLogService */
        $motTestLogService = $serviceLocator->get(MotTestLogService::class);
        /** @var ElasticSearchService $elasticSearchService */
        $elasticSearchService = $serviceLocator->get('ElasticSearchService');
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new MotTestLogController(
            $motTestLogService,
            $elasticSearchService,
            $entityManager
        );
    }
}
