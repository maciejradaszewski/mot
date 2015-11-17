<?php

namespace DvsaClient\Factory;

use Application\Service\CatalogService;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaClient\MapperFactory as Factory;

class TesterGroupAuthorisationMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var MapperFactoryFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(Factory::class);
        /** @var CatalogService $catalog */
        $catalog = $serviceLocator->get('CatalogService');

        return new TesterGroupAuthorisationMapper(
            $mapperFactory->TesterQualificationStatus,
            $catalog
        );
    }
}
