<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Service\PasswordService;
use Dvsa\Mot\Frontend\RegistrationModule\Step\PasswordStep;
use Zend\View\Model\ViewModel;

/**
 * Password Controller.
 */
class PasswordController extends AbstractRegistrationController
{
    const PAGE_TITLE = 'Create a password';

    /**
     * @var PasswordService
     */
    private $passwordService;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->doStepLogic(PasswordStep::STEP_ID, self::PAGE_TITLE, self::DEFAULT_SUB_TITLE);
    }
}