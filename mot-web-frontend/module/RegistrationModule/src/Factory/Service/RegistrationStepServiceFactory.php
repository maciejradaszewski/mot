<?php

namespace Dvsa\Mot\Frontend\RegistrationModule\Factory\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\AccountSummaryStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\AddressStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CompletedStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CreateAccountStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\DetailsStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\PasswordStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\SecurityQuestionOneStep;
use Dvsa\Mot\Frontend\RegistrationModule\Step\SecurityQuestionTwoStep;
use DvsaCommon\InputFilter\Registration\AddressInputFilter;
use DvsaCommon\InputFilter\Registration\DetailsInputFilter;
use DvsaCommon\InputFilter\Registration\PasswordInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionFirstInputFilter;
use DvsaCommon\InputFilter\Registration\SecurityQuestionSecondInputFilter;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegistrationStepServiceFactory implements FactoryInterface
{
    /**
     * @var RegistrationSessionService
     */
    private $sessionService;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegistrationStepService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sessionService = $serviceLocator->get(RegistrationSessionService::class);

        $steps = $this->createSteps($this->sessionService);

        return new RegistrationStepService($steps);
    }

    /**
     * @return array
     */
    public function createSteps(RegistrationSessionService $sessionService)
    {
        $steps = [
            new CreateAccountStep($sessionService, new InputFilter()),
            new DetailsStep($sessionService, new DetailsInputFilter()),
            new AddressStep($sessionService, new AddressInputFilter()),
            new SecurityQuestionOneStep($sessionService, new SecurityQuestionFirstInputFilter()),
            new SecurityQuestionTwoStep($sessionService, new SecurityQuestionSecondInputFilter()),
            new PasswordStep($sessionService, new PasswordInputFilter()),
            new AccountSummaryStep($sessionService, new InputFilter()),
            new CompletedStep($sessionService, new InputFilter()),
        ];

        return $steps;
    }
}
