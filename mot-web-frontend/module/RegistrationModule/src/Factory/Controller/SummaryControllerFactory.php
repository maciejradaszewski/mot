<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Factory\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Controller\SummaryController;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for SummaryController instances.
 */
class SummaryControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SummaryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $registrationService = $serviceLocator->get(RegistrationStepService::class);

        return new SummaryController($registrationService);
    }
}
