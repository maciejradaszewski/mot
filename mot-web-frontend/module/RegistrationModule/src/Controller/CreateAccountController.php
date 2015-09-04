<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CreateAccountStep;

/**
 * CreateAccount Controller.
 */
class CreateAccountController extends AbstractRegistrationController
{
    const PAGE_TITLE = 'Create an account';

    /**
     * @var RegistrationSessionService
     */
    protected $session;

    /**
     * @param RegistrationStepService    $registrationService
     * @param RegistrationSessionService $session
     */
    public function __construct(
        RegistrationStepService $registrationService,
        RegistrationSessionService $session
    ) {
        parent::__construct($registrationService);
        $this->session = $session;
    }

    public function indexAction()
    {
        $this->session->destroy();

        return $this->doStepLogic(CreateAccountStep::STEP_ID, self::PAGE_TITLE, null);
    }
}
