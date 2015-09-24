<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\Factory\Service;

use Core\Factory\StepServiceFactory;
use Core\Service\SessionService;
use DvsaCommon\InputFilter\Event\OutcomeInputFilter;
use DvsaCommon\InputFilter\Event\RecordInputFilter;
use Event\Service\EventSessionService;
use Event\Service\EventStepService;
use Event\Step\CompletedStep;
use Event\Step\OutcomeStep;
use Event\Step\RecordStep;
use Event\Step\SummaryStep;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventStepServiceFactory extends StepServiceFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventStepService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sessionService = $serviceLocator->get(EventSessionService::class);

        $steps = $this->createSteps($this->sessionService);

        return new EventStepService($steps);
    }

    /**
     * @return array
     */
    public function createSteps(SessionService $sessionService)
    {
        $steps = [
            new RecordStep($sessionService, new RecordInputFilter()),
            new OutcomeStep($sessionService, new OutcomeInputFilter()),
            new SummaryStep($sessionService, new InputFilter()),
            new CompletedStep($sessionService, new InputFilter()),
        ];

        return $steps;
    }
}
