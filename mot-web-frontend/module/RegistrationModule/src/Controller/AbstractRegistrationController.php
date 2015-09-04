<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModule\Controller;

use Core\Controller\AbstractDvsaActionController;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Zend\View\Model\ViewModel;

/**
 * Abstract Registration Controller.
 */
abstract class AbstractRegistrationController extends AbstractDvsaActionController
{
    /** Registration journey's default subtitle */
    const DEFAULT_SUB_TITLE = 'Create an account';

    /**
     * Registration journey's default layout.
     *
     * @TODO: (ABN) this can be in the module's config
     */
    const DEFAULT_LAYOUT = 'layout/layout-govuk.phtml';

    /**
     * @var RegistrationStepService
     */
    protected $registrationService;

    /**
     * @param RegistrationStepService $registrationService
     */
    public function __construct(
        RegistrationStepService $registrationService
    ) {
        $this->registrationService = $registrationService;
    }

    /**
     * @param string      $title
     * @param string|null $subtitle Do not set a default, some pages must not have a subtitle.
     * @param string|null $progress
     */
    protected function setLayout($title, $subtitle = null, $progress = null)
    {
        $this->layout(self::DEFAULT_LAYOUT);
        $this->layout()
            ->setVariable('pageTitle', $title)
            ->setVariable('pageSubTitle', $subtitle)
            ->setVariable('progress', $progress);
    }

    /**
     * All of our step controllers have the same logic flow to them.
     * For this reason, we have moved the logic into this abstract class.
     *
     * @param string $stepID
     * @param string $title
     * @param string $subtitle
     *
     * @throws \Exception
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function doStepLogic($stepID, $title, $subtitle = null)
    {
        $step = $this->registrationService->setActiveById($stepID)->current();
        $step->load();

        if ($this->getRequest()->isPost()) {
            $step->readFromArray($this->getRequest()->getPost()->toArray());
            $step->save();

            if ($step->validate()) {
                return $this->redirect()->toRoute($this->registrationService->next()->route());
            }
        } else {
            if ($step !== $this->registrationService->first()) {
                if ($this->registrationService->previous()->load()->validate() === false) {
                    return $this->redirect()->toRoute($this->registrationService->previous()->route());
                }
            }
        }

        $this->setLayout($title, $subtitle, $step->getProgress());

        return new ViewModel($step->toViewArray());
    }
}
