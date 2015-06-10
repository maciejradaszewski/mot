<?php

namespace UserAdmin\Factory\Controller;

use UserAdmin\Controller\UserProfileController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\TesterQualificationStatusService;
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
        $testerQualificationStatusService = $appServiceLocator->get(TesterQualificationStatusService::class);

        $controller = new UserProfileController(
            $authorisationService,
            $accountAdminService,
            $testerQualificationStatusService
        );

        return $controller;
    }
}
