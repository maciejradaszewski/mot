<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Step\AccountSummaryStep;
use Zend\View\Model\ViewModel;

/**
 * Summary Controller.
 */
class SummaryController extends RegistrationBaseController
{
    const PAGE_TITLE = 'Account summary';

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        return $this->doStepLogic(AccountSummaryStep::STEP_ID, self::PAGE_TITLE, self::DEFAULT_SUB_TITLE);
    }
}
