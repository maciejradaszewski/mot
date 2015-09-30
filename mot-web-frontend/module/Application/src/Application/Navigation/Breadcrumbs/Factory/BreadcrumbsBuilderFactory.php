<?php

namespace Application\Navigation\Breadcrumbs\Factory;

use Application\Navigation\Breadcrumbs\BreadcrumbsBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BreadcrumbsBuilderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $resolvers = $config['breadcrumbs']['resolvers'];
        $layout = $serviceLocator->get('viewhelpermanager')->get('layout');

        return new BreadcrumbsBuilder($resolvers, $serviceLocator, $layout);
    }
}
