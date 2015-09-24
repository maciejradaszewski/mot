<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Factory\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\Mot\Frontend\RegistrationModule\Controller\AddressController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for AddressController instances.
 */
class AddressControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AddressController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $stepService = $serviceLocator->get(RegistrationStepService::class);

        return new AddressController($stepService);
    }
}
