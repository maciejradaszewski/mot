<?php

namespace UserAdmin\Factory\Controller;

use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use UserAdmin\Controller\UserProfileController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for {@link \UserAdmin\Controller\UserProfileController}
 */
class UserProfileControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        $authorisationService = $appServiceLocator->get("AuthorisationService");
        $accountAdminService = $appServiceLocator->get(HelpdeskAccountAdminService::class);
        $testerGroupAuthorisationMapper = $appServiceLocator->get(TesterGroupAuthorisationMapper::class);

        $controller = new UserProfileController(
            $authorisationService,
            $accountAdminService,
            $testerGroupAuthorisationMapper
        );

        return $controller;
    }
}
