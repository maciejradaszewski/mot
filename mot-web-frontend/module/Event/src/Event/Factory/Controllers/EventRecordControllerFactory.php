<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\Factory\Controllers;

use Event\Controller\EventRecordController;
use Event\Service\EventSessionService;
use Event\Service\EventStepService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * EventRecordController Factory.
 */
class EventRecordControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventRecordController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();

        /** @var EventStepService $stepService */
        $stepService = $controllerManager->get(EventStepService::class);

        /** @var EventSessionService $sessionService */
        $sessionService = $controllerManager->get(EventSessionService::class);

        return new EventRecordController($stepService, $sessionService);
    }
}
