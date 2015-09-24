<?php

namespace Core\Factory;

use Core\Service\StepService;
use Core\Service\SessionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StepServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return StepService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sessionService = $serviceLocator->get(SessionService::class);

        $steps = $this->createSteps($sessionService);

        return new StepService($steps);
    }

    /**
     * @return array
     */
    public function createSteps(SessionService $sessionService)
    {
        return [];
    }
}
