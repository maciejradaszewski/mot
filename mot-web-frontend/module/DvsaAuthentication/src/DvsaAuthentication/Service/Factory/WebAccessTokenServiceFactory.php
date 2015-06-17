<?php

namespace DvsaAuthentication\Service\Factory;

use DvsaAuthentication\Service\WebAccessTokenService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WebAccessTokenServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $openAMConfig = $openAMCookieName = $sl->get('config')['dvsa_authentication']['openAM'];
        $request = $sl->get('Request');
        return new WebAccessTokenService($request, $openAMConfig);
    }
}
