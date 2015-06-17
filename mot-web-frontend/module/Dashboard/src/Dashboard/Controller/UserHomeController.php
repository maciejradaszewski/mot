<?php
namespace Dashboard\Controller;

use Account\Service\SecurityQuestionService;
use Application\Data\ApiPersonalDetails;
use Application\Helper\PrgHelper;
use Application\Service\CatalogService;
use Application\Service\LoggedInUserManager;
use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
use Core\Controller\AbstractAuthActionController;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Model\Dashboard;
use Dashboard\Model\PersonalDetails;
use Dashboard\PersonStore;
use Dashboard\ViewModel\SecurityQuestionViewModel;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\CountryOfRegistrationCode;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\HttpRestJson\Exception\NotFoundException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * Controller for dashboard
 */
class UserHomeController extends AbstractAuthActionController
{
    const ROUTE = 'user-home';

    const PAGE_TITLE    = 'Reset PIN';
    const PAGE_SUBTITLE = 'MOT Testing Service';

    const ERR_PIN_UPDATE_FAIL = 'There was a problem updating your PIN.';
    const ERR_COMMON_API = 'Something went wrong.';

    /** @var  LoggedInUserManager */
    private $loggedIdUserManager;
    /** @var  ApiPersonalDetails */
    private $personalDetailsService;
    /** @var  PersonStore */
    private $personStoreService;
    /** @var  ApiDashboardResource */
    private $dashboardResourceService;
    /** @var CatalogService  */
    private $catalogService;
    /** @var WebAcknowledgeSpecialNoticeAssertion  */
    private $acknowledgeSpecialNoticeAssertion;
    /** @var SecurityQuestionService */
    protected $service;
    /** @var UserAdminSessionManager */
    protected $userAdminSessionManager;

    public function __construct(
        LoggedInUserManager $loggedIdUserManager,
        ApiPersonalDetails $personalDetailsService,
        PersonStore $personStoreService,
        ApiDashboardResource $dashboardResourceService,
        CatalogService $catalogService,
        WebAcknowledgeSpecialNoticeAssertion $acknowledgeSpecialNoticeAssertion,
        SecurityQuestionService $securityQuestionService,
        UserAdminSessionManager $userAdminSessionManager
    ) {
        $this->loggedIdUserManager = $loggedIdUserManager;
        $this->personalDetailsService = $personalDetailsService;
        $this->personStoreService = $personStoreService;
        $this->dashboardResourceService = $dashboardResourceService;
        $this->catalogService = $catalogService;
        $this->acknowledgeSpecialNoticeAssertion = $acknowledgeSpecialNoticeAssertion;
        $this->service = $securityQuestionService;
        $this->userAdminSessionManager = $userAdminSessionManager;
    }

    public function userHomeAction()
    {
        $identity = $this->getIdentity();
        $personId = $identity->getUserId();

        // TODO this should be moved to loginAction
        $this->loggedIdUserManager->discoverCurrentLocation($identity->getCurrentVts());

        $dashboard = $this->getDashboardDetails($personId);
        $authenticatedData = $this->getAuthenticatedData();
        $specialNotice = array_merge(
            $dashboard->getSpecialNotice()->toArray(),
            [
                'canRead' => $authenticatedData['canRead'],
                'canAcknowledge' => $authenticatedData['canAcknowledge']
            ]
        );

        return array_merge(
            [
                'dashboard' => $dashboard
            ],
            $authenticatedData,
            [
                'specialNotice' => $specialNotice
            ]
        );
    }

    public function profileAction()
    {
        $this->userAdminSessionManager->deleteUserAdminSession();

        return $this->getAuthenticatedData();
    }

    public function securitySettingsAction()
    {
        $userId = $this->getIdentity()->getUserId();

        if ($this->userAdminSessionManager->isUserAuthenticated($userId) !== true) {
            $this->redirect()->toUrl(PersonUrlBuilderWeb::securityQuestions());
        }

        $personalInfo = $this->getAuthenticatedData();

        /** @var PersonalDetails $personalDetails */
        $personalDetails = $personalInfo['personalDetails'];

        $returnData = [
            'fullName' => $personalDetails->getFullName(),
            'config'   => $this->getConfig(),
            'userId'   => $userId,
        ];

        if ($this->getRequest()->isPost()) {

            try {

                $apiUrl = PersonUrlBuilder::resetPin($userId);
                $responseData = $this->getRestClient()->put($apiUrl, null);

                $returnData['pin'] = $responseData['data']['pin'];

            } catch (\Exception $e) {
                if ($e instanceof GeneralRestException) {
                    $errMsg = self::ERR_PIN_UPDATE_FAIL;
                } else {
                    $errMsg = self::ERR_COMMON_API;
                }
                $this->flashMessenger()->addErrorMessage($errMsg);
            }

        } else {
            $this->layout('layout/layout-govuk.phtml');
        }

        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE);
        $this->layout()->setVariable('pageTitle', self::PAGE_TITLE);

        return $returnData;
    }

    public function editAction()
    {
        $identity = $this->getIdentity();
        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::PROFILE_EDIT_OWN_CONTACT_DETAILS)) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::profile());
        }
        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            try {
                $this->personStoreService->update($identity->getUserId(), $data);
                return $this->redirect()->toUrl(PersonUrlBuilderWeb::profile());

            } catch (ValidationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
                $data['phone'] = $data['phoneNumber']; // fixing field naming inconcistency
                return $this->getAuthenticatedData($data);
            }
        }
        return $this->getAuthenticatedData();
    }

    /**
     * @param $personId int
     *
     * @return Dashboard
     */
    private function getDashboardDetails($personId)
    {
        $dashboardData = $this->dashboardResourceService->get($personId);

        return new Dashboard($dashboardData);
    }

    /**
     * @return array
     */
    private function getCountries()
    {
        $allCountries = $this->catalogService->getCountriesOfRegistrationByCode();

        $countries=[];
        foreach ($allCountries as $code => $country) {
            $countries[$code] = $country;

            if (CountryOfRegistrationCode::NOT_APPLICABLE === $code) {
                break;
            }
        }

        return $countries;
    }

    /**
     * @param null $personalDetailsData
     * @return array
     */
    private function getAuthenticatedData($personalDetailsData = null)
    {
        $personId = (int)$this->params()->fromRoute('id', null);
        $identity = $this->getIdentity();

        if ($personId == 0) {
            $personId = $identity->getUserId();
        }

        $isAllowEdit = ($personId > 0 && $identity->getUserId() == $personId
        && $this->getAuthorizationService()->isGranted(PermissionInSystem::PROFILE_EDIT_OWN_CONTACT_DETAILS)
        );

        $personalDetailsData = array_merge(
            $this->personalDetailsService->getPersonalDetailsData($personId),
            $personalDetailsData ?: []
        );

        $personalDetails = new PersonalDetails($personalDetailsData);

        $authorisations = $this->personalDetailsService->getPersonalAuthorisationForMotTesting($personId);

        return [
            'personalDetails'     => $personalDetails,
            'isAllowEdit'         => $isAllowEdit,
            'motAuthorisations'   => $authorisations,
            'isViewingOwnProfile' => ($identity->getUserId() == $personId),
            'countries'           => $this->getCountries(),
            'canAcknowledge'      => $this->acknowledgeSpecialNoticeAssertion->isGranted($personId),
            'canRead'             => $this->getAuthorizationService()->isGranted(PermissionInSystem::SPECIAL_NOTICE_READ)
        ];
    }
}
