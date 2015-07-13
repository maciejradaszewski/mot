<?php

namespace DvsaClient\Factory;

use Application\Service\CatalogService;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\MapperFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TesterGroupAuthorisationMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);
        /** @var CatalogService $catalog */
        $catalog = $serviceLocator->get('CatalogService');

        return new TesterGroupAuthorisationMapper (
            $mapperFactory->TesterQualificationStatus,
            $catalog
        );
    }
}
