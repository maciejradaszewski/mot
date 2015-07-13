<?php

namespace UserAdmin\Controller;

use Core\Controller\AbstractAuthActionController;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\Presenter\UserProfilePresenter;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\ViewModel\UserProfile\TesterAuthorisationViewModel;
use Zend\View\Model\ViewModel;

/**
 * Set account to be unclaimed for security questions reset journey.
 */
class ResetAccountClaimByPostController extends AbstractAuthActionController
{
    const ROUTE_INDEX = 'user_admin/user-profile/claim-reset';

    const PAGE_SUBTITLE_INDEX = 'User profile';

    const PAGE_TITLE_INDEX = 'Reclaim account';

    const RECLAIM_ACCOUNT_SUCCESS = 'Account reclaim by post was requested';

    /**
     * @var HelpdeskAccountAdminService
     */
    private $accountAdminService;

    private $testerGroupAuthorisationMapper;

    private $authorisationService;

    public function __construct(
        HelpdeskAccountAdminService $accountAdminService,
        TesterGroupAuthorisationMapper $testerGroupAuthorisationMapper,
        MotAuthorisationServiceInterface $authorisationService
    )
    {
        $this->accountAdminService = $accountAdminService;
        $this->testerGroupAuthorisationMapper = $testerGroupAuthorisationMapper;
        $this->authorisationService = $authorisationService;
    }

    public function indexAction()
    {
        $personId = $this->params()->fromRoute('personId');

        if ($this->getRequest()->isPost() === true) {
            return $this->requestReclaim($personId);
        }

        return $this->displayConfirmationScreen($personId);
    }

    private function requestReclaim($personId)
    {
        try {
            $this->accountAdminService->resetAccount($personId);

            $this->flashMessenger()->addSuccessMessage(self::RECLAIM_ACCOUNT_SUCCESS);
        } catch (ValidationException $ex) {
            foreach ($ex->getDisplayMessages() as $errorMessage) {
                $this->addErrorMessage($errorMessage);
            }
        }
        return $this->redirect()->toUrl(
            $this->buildUrlWithCurrentSearchQuery(UserAdminUrlBuilderWeb::userProfile($personId))
        );
    }

    private function displayConfirmationScreen($personId)
    {
        $person = $this->accountAdminService->getUserProfile($personId);

        $profilePresenter = new UserProfilePresenter($person, $this->getTesterAuthorisationViewModel($personId));
        $profilePresenter->setPersonId($personId);

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE_INDEX);
        $this->layout()->setVariable('pageTitle', self::PAGE_TITLE_INDEX);

        $breadcrumbs = [
            'User search' => $this->buildUrlWithCurrentSearchQuery(UserAdminUrlBuilderWeb::of()->userResults()),
            $profilePresenter->displayTitleAndFullName() =>
                $this->buildUrlWithCurrentSearchQuery(UserAdminUrlBuilderWeb::userProfile($personId)),
        ];
        $this->layout()->setVariable('progressBar', ['breadcrumbs' => $breadcrumbs]);

        return new ViewModel(
            [
                'profilePresenter' => $profilePresenter,
                'userProfileUrl' => $this->buildUrlWithCurrentSearchQuery(
                    UserAdminUrlBuilderWeb::userProfile($personId)
                ),
                'resetClaimAccountUrlPost' => $this->buildUrlWithCurrentSearchQuery(
                    UserAdminUrlBuilderWeb::userProfileClaimAccountPost($personId)
                ),
            ]
        );
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
