<?php

namespace UserAdmin\Factory\Controller;

use UserAdmin\Controller\ResetAccountClaimByPostController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Factory for {@link \UserAdmin\Controller\ResetAccountClaimByPostController}.
 */
class ResetAccountClaimByPostControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        /** @var HelpdeskAccountAdminService */
        $accountAdminService = $appServiceLocator->get(HelpdeskAccountAdminService::class);
        $testerQualificationStatusService = $appServiceLocator->get(TesterQualificationStatusService::class);

        return new ResetAccountClaimByPostController(
            $accountAdminService,
            $testerQualificationStatusService
        );
    }
}
