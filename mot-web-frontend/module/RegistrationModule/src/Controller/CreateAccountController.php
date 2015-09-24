<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Core\Service\StepService;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CreateAccountStep;

/**
 * CreateAccount Controller.
 */
class CreateAccountController extends RegistrationBaseController
{
    const PAGE_TITLE = 'Create an account';

    /**
     * @var RegistrationSessionService
     */
    protected $session;

    /**
     * @param StepService                $stepService
     * @param RegistrationSessionService $session
     */
    public function __construct(
        StepService $stepService,
        RegistrationSessionService $session
    ) {
        parent::__construct($stepService);
        $this->session = $session;
    }

    public function indexAction()
    {
        $this->session->destroy();

        return $this->doStepLogic(CreateAccountStep::STEP_ID, self::PAGE_TITLE, null);
    }
}
