<?php

namespace UserAdmin\Factory\Service;

use DvsaClient\MapperFactory;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TesterQualificationStatusServiceFactory implements FactoryInterface
{
    /**
     * Create TesterQualificationStatusService service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return TesterQualificationStatusService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);
        $catalogService = $serviceLocator->get('CatalogService');

        $service = new TesterQualificationStatusService(
            $mapperFactory->TesterQualificationStatus,
            $catalogService
        );

        return $service;
    }
}
