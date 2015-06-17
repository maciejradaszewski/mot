<?php

namespace Application\View\HelperFactory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\View\Helper\AuthorisationHelper;

class AuthorisationHelperFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $viewHelperServiceLocator)
    {
        $sl = $viewHelperServiceLocator->getServiceLocator();
        $authService = $sl->get('AuthorisationService');

        $helper = new AuthorisationHelper($authService);

        return $helper;
    }
}
