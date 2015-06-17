<?php

namespace CensorApi\Factory\Service;

use CensorApi\Service\CensorService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CensorServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $censorService = new CensorService($serviceLocator->get("CensorPhraseRepository"));
        return $censorService;
    }
}
