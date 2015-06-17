<?php

namespace Account\Controller;

use Account\AbstractClass\AbstractSecurityQuestionController;
use Account\Service\SecurityQuestionService;
use Account\ViewModel\SecurityQuestionViewModel;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Escaper;

/**
 * Class SecurityQuestionController
 * @package Account\Controller
 */
class SecurityQuestionController extends AbstractSecurityQuestionController
{
    const PAGE_TITLE    = 'Forgotten Password';
    const PAGE_SUBTITLE = 'MOT Testing Service';

    public function __construct(SecurityQuestionService $securityQuestionService)
    {
        parent::__construct($securityQuestionService);
    }

    /**
     * This action is the end point to enter the question answer for the help desk
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $personId   = $this->params()->fromRoute('personId');
        $questionNumber = $this->params()->fromRoute('questionNumber');
        $viewModel = new SecurityQuestionViewModel($this->service);

        $view = $this->index($personId, $questionNumber, $viewModel);

        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE);
        $this->layout()->setVariable('pageTitle', self::PAGE_TITLE);
        $this->layout()->setVariable('progress', $this->service->getStep());

        return $view;
    }
}
