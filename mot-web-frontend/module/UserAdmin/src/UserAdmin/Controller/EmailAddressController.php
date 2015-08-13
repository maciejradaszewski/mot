<?php

namespace UserAdmin\Controller;

use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\Role;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use UserAdmin\Presenter\UserProfilePresenter;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\ViewModel\UserProfile\TesterAuthorisationViewModel;
use Zend\View\Model\ViewModel;

/**
 * Class EmailAddressController
 * @package UserAdmin\Controller
 */
class EmailAddressController extends AbstractDvsaMotTestController
{
    const PAGE_TITLE          = 'Change email address';
    const PAGE_SUBTITLE_INDEX = 'User profile';

    /** @var HelpdeskAccountAdminService */
    private $userAccountAdminService;
    /** @var MotAuthorisationServiceInterface*/
    private $authorisationService;

    private $testerGroupAuthorisationMapper;


    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        HelpdeskAccountAdminService $userAccountAdminService,
        TesterGroupAuthorisationMapper $testerGroupAuthorisationMapper
    ) {
        $this->userAccountAdminService = $userAccountAdminService;
        $this->authorisationService = $authorisationService;
        $this->testerGroupAuthorisationMapper = $testerGroupAuthorisationMapper;
    }

    /**
     * Action to display the user profile for the helpdesk
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $this->authorisationService->assertGranted(PermissionInSystem::PROFILE_EDIT_OTHERS_EMAIL_ADDRESS);

        $personId = $this->params()->fromRoute('personId');
        $presenter = $this->createPresenter($personId);
        $email = $emailConfirm = $presenter->displayEmail();

        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost()->toArray();

            if (true === ($validated = $this->validate($personId, $params['email'], $params['emailConfirm']))) {
                $validated = $this->callApi($personId, $params['email'], $params['emailConfirm']);
            };

            if ($validated) {
                return $this->redirect()->toUrl(UserAdminUrlBuilderWeb::of()->UserProfile($personId));
            }
            
            $email        = $params['email'];
            $emailConfirm = $params['emailConfirm'];
        }
        $viewModel = $this->createViewModel($personId, self::PAGE_TITLE, $presenter, false, $email, $emailConfirm);
        $viewModel->setTemplate(UserProfilePresenter::CHANGE_EMAIL_TEMPLATE);

        return $viewModel;
    }

    /**
     * @param integer $personId
     * @param string $email
     * @param string $emailConfirm
     * @return bool
     */
    private function validate($personId, $email, $emailConfirm)
    {
        $hasErrors = false;
        if ($email != $emailConfirm) {
            $this->addErrorMessage("Emails do not match");
            $hasErrors = true;
        }
        if ($email == '') {
            $this->addErrorMessage("Email cannot be blank");
            $hasErrors = true;
        }
        return !$hasErrors;
    }

    /**
     * @param integer $personId
     * @param string $email
     * @return bool
     */
    private function callApi($personId, $email) {
        $hasErrors = false;
        try {
            $this->userAccountAdminService->updatePersonContactEmail($personId, $email);
        }
        catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
            $hasErrors = true;
        }
        return !$hasErrors;
    }

    private function createPresenter($personId)
    {
        $presenter = new UserProfilePresenter(
            $this->userAccountAdminService->getUserProfile($personId),
            $this->getTesterAuthorisationViewModel($personId),
            null,
            $this->authorisationService->isGranted(PermissionInSystem::VIEW_OTHER_USER_PROFILE_DVSA_USER) &&
            !$this->authorisationService->hasRole(Role::CUSTOMER_SERVICE_CENTRE_OPERATIVE)
        );
        $presenter->setPersonId($personId);
        return $presenter;
    }

    /**
     * Create the view model with all the information needed
     *
     * @param int                  $personId
     * @param string               $pageTitle
     * @param EmailAddressPresenter $presenter
     * @param bool                 $isProfile
     *
     * @return ViewModel
     */
    private function createViewModel(
        $personId,
        $pageTitle,
        UserProfilePresenter $presenter,
        $isProfile = false,
        $emailValue,
        $emailConfirmValue
    )
    {
        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE_INDEX . ' - ' . $presenter->displayFullName());
        $this->layout()->setVariable('pageTitle', $pageTitle);
        $breadcrumbs = [
            'User search' => $this->buildUrlWithCurrentSearchQuery(UserAdminUrlBuilderWeb::of()->userSearch()),
            $presenter->displayTitleAndFullName() =>
                $isProfile === false ? $this->buildUrlWithCurrentSearchQuery(
                    UserAdminUrlBuilderWeb::of()->UserProfile($personId)
                ) : '',
            'Change email address' => ''
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);
        $this->layout('layout/layout-govuk.phtml');

        $resultViewModel = new ViewModel(
            [
                'presenter' => $presenter,
                'searchResultsUrl' => $this->buildUrlWithCurrentSearchQuery(
                    UserAdminUrlBuilderWeb::of()->userResults()
                ),
                'searchQueryParams' => $this->getRequest()->getQuery()->toArray(),
                'emailAddressUrl' => UserAdminUrlBuilderWeb::emailChange($personId),
                'emailValue'      => $emailValue,
                'emailConfirmValue'      => $emailConfirmValue
            ]
        );

        return $resultViewModel;
    }

    /**
     * Build a url with the query params
     *
     * @param string $url
     *
     * @return string
     */
    private function buildUrlWithCurrentSearchQuery($url)
    {
        $params = $this->getRequest()->getQuery()->toArray();
        if (empty($params)) {
            return $url;
        }
        return $url . '?' . http_build_query($params);
    }

    private function getTesterAuthorisationViewModel($personId)
    {
        return new TesterAuthorisationViewModel(
            $personId,
            $this->testerGroupAuthorisationMapper->getAuthorisation($personId),
            $this->authorisationService
        );
    }
}
