<?php

namespace Application\Navigation\Breadcrumbs\Handler\Factory;

use Application\Navigation\Breadcrumbs\Handler\SiteNameResolver;
use DvsaCommon\HttpRestJson\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteNameResolverFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $url = $sl->get('viewhelpermanager')->get('url');
        $client = $sl->get(Client::class);

        return new SiteNameResolver($client, $url);
    }
}
