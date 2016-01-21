<?php

namespace Core\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UrlHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helperManager = $serviceLocator->get('ViewHelperManager');
        $url = $helperManager->get('url');

        return $url;
    }
}
