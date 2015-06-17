<?php

namespace UserAdmin\Controller;

use Account\AbstractClass\AbstractSecurityQuestionController;
use Account\Service\SecurityQuestionService;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\ViewModel\SecurityQuestionViewModel;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Escaper;

/**
 * Class SecurityQuestionController
 * @package UserAdmin\Controller
 */
class SecurityQuestionController extends AbstractSecurityQuestionController
{
    const PAGE_TITLE    = 'User profile';
    const PAGE_SUBTITLE = 'Authenticate %s';

    public function __construct(SecurityQuestionService $securityQuestionService)
    {
        parent::__construct($securityQuestionService);
    }

    /**
     * This action is the end point to enter the question answer for the help desk
     *
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     */
    public function indexAction()
    {
        $this->getAuthorizationService()->assertGranted(PermissionInSystem::SECURITY_QUESTION_READ_USER);

        $personId   = $this->params()->fromRoute('personId');
        $questionNumber = $this->params()->fromRoute('questionNumber', 1);
        $viewModel = new SecurityQuestionViewModel($this->service);

        $view = $this->index($personId, $questionNumber, $viewModel);

        if ($view instanceof ViewModel) {
            $this->layout()->setVariable('pageTitle', self::PAGE_TITLE);
            $this->layout()->setVariable(
                'pageSubTitle',
                sprintf(self::PAGE_SUBTITLE, $this->viewModel->getPerson()->getFullName())
            );

            $breadcrumbs = [
                'User search' => UserAdminUrlBuilderWeb::of()->userSearch(),
                'Authenticate user' => '',
            ];
            $this->layout()->setVariable('progressBar', ['breadcrumbs' => $breadcrumbs]);
        }

        return $view;
    }
}
