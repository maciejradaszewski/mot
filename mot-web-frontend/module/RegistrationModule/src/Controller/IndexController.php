<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Core\Controller\AbstractDvsaActionController;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\CreateAccountStep;

/**
 * Index Controller.
 */
class IndexController extends AbstractDvsaActionController
{
    /**
     * @var RegistrationStepService
     */
    private $registrationService;

    /**
     * @param RegistrationStepService $registrationService
     */
    public function __construct(
        RegistrationStepService $registrationService
    ) {
        $this->registrationService = $registrationService;
    }

    /**
     * As a part of the security story, it was suggested to start the registration
     * process by immediately throwing a HTTP 302 redirect. This is seen as the first of
     * many small steps to help protect the system.
     *
     * See the honeypot forms wiki article for more info.
     *
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        $step = $this->registrationService->setActiveById(CreateAccountStep::STEP_ID)->current();

        return $this->redirect()->toRoute($step->route());
    }
}
