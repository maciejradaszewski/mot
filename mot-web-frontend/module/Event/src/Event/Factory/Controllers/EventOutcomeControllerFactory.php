<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\Factory\Controllers;

use Event\Controller\EventOutcomeController;
use Event\Service\EventStepService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventOutcomeController Factory.
 */
class EventOutcomeControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventOutcomeController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();

        /** @var EventStepService $stepService */
        $stepService = $controllerManager->get(EventStepService::class);

        return new EventOutcomeController($stepService);
    }
}
