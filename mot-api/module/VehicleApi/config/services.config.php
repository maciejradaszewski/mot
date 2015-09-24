<?php

use VehicleApi\Service\VehicleService;
use VehicleApi\Service\VehicleSearchService;
use VehicleApi\Factory\Service\VehicleServiceFactory;
use VehicleApi\Factory\Service\VehicleSearchParamFactory;
use VehicleApi\Factory\Service\VehicleSearchServiceFactory;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;

$config = [
    'factories' => [
        VehicleService::class => VehicleServiceFactory::class,
        VehicleSearchService::class => VehicleSearchServiceFactory::class,
        VehicleSearchParam::class => VehicleSearchParamFactory::class,
        'Zend\Log\Logger' => function($sm){
            $logger = new Zend\Log\Logger;
            $writer = new Zend\Log\Writer\Stream('./data/log/'.date('Y-m-d').'-error.log');
            $logger->addWriter($writer);
            return $logger;
        },
    ],
];

return $config;