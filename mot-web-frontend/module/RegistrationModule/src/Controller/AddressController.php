<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Step\AddressStep;
use Zend\View\Model\ViewModel;

/**
 * Address Controller.
 */
class AddressController extends RegistrationBaseController
{
    const PAGE_TITLE = 'Your address';

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        return $this->doStepLogic(AddressStep::STEP_ID, self::PAGE_TITLE, self::DEFAULT_SUB_TITLE);
    }
}
