<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\Factory\Controllers;

use Event\Controller\EventCompletedController;
use Event\Service\EventSessionService;
use Event\Service\EventStepService;
use Event\Service\ManualEventService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * EventCompletedController Factory.
 */
class EventCompletedControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventSummaryController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();

        /** @var EventStepService $stepService */
        $stepService = $controllerManager->get(EventStepService::class);

        /** @var EventSessionService $sessionService */
        $sessionService = $controllerManager->get(EventSessionService::class);

        /** @var ManualEventService $manualEventService */
        $manualEventService = $controllerManager->get(ManualEventService::class);

        return new EventCompletedController($stepService, $sessionService, $manualEventService);
    }
}
