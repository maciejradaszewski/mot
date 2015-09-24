<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Factory\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Controller\CompletedController;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegisterUserService;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Zend\Db\TableGateway\Exception\RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for CompleteController instances.
 */
class CompletedControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CompletedController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $stepService = $serviceLocator->get(RegistrationStepService::class);
        $registerUserService = $serviceLocator->get(RegisterUserService::class);
        $session = $serviceLocator->get(RegistrationSessionService::class);

        $config = $serviceLocator->get('Config');

        $helpdeskConfig = isset($config['helpdesk']) ? $config['helpdesk'] : null;
        if (!$helpdeskConfig) {
            throw new RuntimeException('Helpdesk details not found in $config["helpdesk"]');
        }

        return new CompletedController($stepService, $registerUserService, $session, $helpdeskConfig);
    }
}
