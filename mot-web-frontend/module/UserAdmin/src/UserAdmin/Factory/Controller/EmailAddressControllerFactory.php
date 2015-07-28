<?php

namespace UserAdmin\Factory\Controller;

use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use UserAdmin\Controller\EmailAddressController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for {@link \UserAdmin\Controller\EmailAddressController}
 */
class EmailAddressControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        $authorisationService = $appServiceLocator->get("AuthorisationService");
        $accountAdminService = $appServiceLocator->get(HelpdeskAccountAdminService::class);
        $testerGroupAuthorisationMapper = $appServiceLocator->get(TesterGroupAuthorisationMapper::class);

        $controller = new EmailAddressController(
            $authorisationService,
            $accountAdminService,
            $testerGroupAuthorisationMapper
        );

        return $controller;
    }
}
