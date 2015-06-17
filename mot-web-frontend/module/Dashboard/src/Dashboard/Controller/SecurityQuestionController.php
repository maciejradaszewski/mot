<?php

namespace Dashboard\Controller;

use Account\AbstractClass\AbstractSecurityQuestionController;
use Account\Service\SecurityQuestionService;
use Dashboard\ViewModel\SecurityQuestionViewModel;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Escaper;

/**
 * Class SecurityQuestionController
 * @package Dashboard\Controller
 */
class SecurityQuestionController extends AbstractSecurityQuestionController
{
    const PAGE_TITLE    = 'Reset PIN';
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
        $personId = $this->getIdentity()->getUserId();
        $questionNumber = $this->params()->fromRoute('questionNumber', 1);
        $viewModel = new SecurityQuestionViewModel($this->service);

        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE);
        $this->layout()->setVariable('pageTitle', self::PAGE_TITLE);

        return $this->index($personId, $questionNumber, $viewModel);
    }
}
