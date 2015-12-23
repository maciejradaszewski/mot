<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\ProfileModule\Controller;

use Application\Data\ApiPersonalDetails;
use Application\Helper\DataMappingHelper;
use Application\Service\CatalogService;
use Application\Service\LoggedInUserManager;
use Core\Controller\AbstractAuthActionController;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Model\PersonalDetails;
use Dashboard\PersonStore;
use Dashboard\Service\TradeRolesAssociationsService;
use Dvsa\Mot\Frontend\ProfileModule\ViewModel\Sidebar\ProfileSidebar;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Constants\FeatureToggle;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\View\Model\ViewModel;

class PersonProfileController extends AbstractAuthActionController
{
    const ROUTE = 'user-home';

    const PAGE_TITLE    = 'Reset PIN';
    const PAGE_SUBTITLE = 'MOT Testing Service';

    const ERR_PIN_UPDATE_FAIL = 'There was a problem updating your PIN.';
    const ERR_COMMON_API = 'Something went wrong.';

    /**
     * @var LoggedInUserManager
     */
    private $loggedIdUserManager;

    /**
     * @var ApiPersonalDetails
     */
    private $personalDetailsService;

    /**
     * @var PersonStore
     */
    private $personStoreService;

    /**
     * @var ApiDashboardResource
     */
    private $dashboardResourceService;

    /**
     * @var CatalogService
     */
    private $catalogService;

    /**
     * @var UserAdminSessionManager
     */
    private $userAdminSessionManager;

    /**
     * @var TesterGroupAuthorisationMapper
     */
    private $testerGroupAuthorisationMapper;

    /**
     * @var MotAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * @var ViewTradeRolesAssertion
     */
    private $viewTradeRolesAssertion;
    /**
     * @var TradeRolesAssociationsService
     */
    protected $tradeRolesAssociationsService;

    public function __construct(
        LoggedInUserManager $loggedIdUserManager,
        ApiPersonalDetails $personalDetailsService,
        PersonStore $personStoreService,
        ApiDashboardResource $dashboardResourceService,
        CatalogService $catalogService,
        UserAdminSessionManager $userAdminSessionManager,
        TesterGroupAuthorisationMapper $testerGroupAuthorisationMapper,
        MotAuthorisationServiceInterface $authorisationService,
        UserAdminSessionManager $userAdminSessionManager,
        ViewTradeRolesAssertion $canViewTradeRolesAssertion,
        TradeRolesAssociationsService $tradeRolesAssociationsService
    ) {
        $this->loggedIdUserManager = $loggedIdUserManager;
        $this->personalDetailsService = $personalDetailsService;
        $this->personStoreService = $personStoreService;
        $this->dashboardResourceService = $dashboardResourceService;
        $this->catalogService = $catalogService;
        $this->userAdminSessionManager = $userAdminSessionManager;
        $this->testerGroupAuthorisationMapper = $testerGroupAuthorisationMapper;
        $this->authorisationService = $authorisationService;
        $this->viewTradeRolesAssertion = $canViewTradeRolesAssertion;
        $this->tradeRolesAssociationsService = $tradeRolesAssociationsService;
    }

    public function indexAction()
    {
        if (false === $this->isFeatureEnabled(FeatureToggle::NEW_PERSON_PROFILE)) {
            return $this->notFoundAction();
        }

        $this->userAdminSessionManager->deleteUserAdminSession();
        $this->layout('layout/layout-govuk.phtml');
        $data = $this->getAuthenticatedData();

        /** @var PersonalDetails $personDetails */
        $personDetails = $data['personalDetails'];

        $profileId = $this->getPersonIdFromRequest();
        $authorisations = $this->testerGroupAuthorisationMapper->getAuthorisation($profileId);

        $breadcrumbs = [
            $personDetails->getFullName() => '',
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        $tradeRolesAndAssociations = $this->tradeRolesAssociationsService->getRolesAndAssociations($this->getIdentity()->getUserId());

        $this->setSidebar(new ProfileSidebar($profileId, $authorisations, $data['isViewingOwnProfile'],
                                                    $this->authorisationService, $tradeRolesAndAssociations
            ));

        return $this->createViewModel('profile-module/index.phtml', [
            'personalDetails'           => $personDetails,
            'isViewingOwnProfile'       => $data['isViewingOwnProfile'],
            'authorisationService'      => $this->authorisationService,
            'systemRoles'               => $this->getSystemRoles($personDetails),
            'tradeRolesAndAssociations' => $this->tradeRolesAssociationsService->getRolesAndAssociations($profileId),
        ]);
    }

    /**
     * @param string $template
     * @param array  $variables
     *
     * @return ViewModel
     */
    private function createViewModel($template, array $variables)
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate($template);
        $viewModel->setVariables($variables);

        return $viewModel;
    }

    /**
     * @param array $personalDetailsData
     *
     * @return array
     */
    private function getAuthenticatedData($personalDetailsData = [])
    {
        $personId = $this->getPersonIdFromRequest();
        $identity = $this->getIdentity();

        $personalDetailsData = array_merge(
            $this->personalDetailsService->getPersonalDetailsData($personId),
            $personalDetailsData
        );

        $personalDetails = new PersonalDetails($personalDetailsData);

        $authorisations = $this->personalDetailsService->getPersonalAuthorisationForMotTesting($personId);

        $isViewingOwnProfile = ($identity->getUserId() == $personId);

        return [
            'personalDetails'      => $personalDetails,
            'motAuthorisations'    => $authorisations,
            'isViewingOwnProfile'  => $isViewingOwnProfile,
            'rolesAndAssociations' => $this->tradeRolesAssociationsService->getRolesAndAssociations($this->getIdentity()->getUserId()),
            'systemRoles'          => $this->getSystemRoles($personalDetails),
        ];
    }

    private function getPersonIdFromRequest()
    {
        $personId = (int) $this->params()->fromRoute('personId', null);
        $identity = $this->getIdentity();

        if ($personId == 0) {
            $personId = $identity->getUserId();
        }

        return $personId;
    }

    /**
     * Gets and returns an array of System (internal) DVLA/DVSA roles.
     *
     * @param PersonalDetails $personalDetails
     *
     * @throws \Exception
     *
     * @return array
     */
    private function getSystemRoles(PersonalDetails $personalDetails)
    {
        $roles = [];
        $systemRoles = $personalDetails->getDisplayableSystemRoles();

        $personSystemRoles = $this->catalogService->getPersonSystemRoles();

        foreach ($systemRoles as $systemRole) {
            $temp = (new DataMappingHelper($personSystemRoles, 'code', $systemRole))
                ->setReturnKeys(['name'])
                ->getValue();

            $temp = $temp['name'];
            $roles[] = $this->createRoleData($systemRole, $temp, 'system');
        }

        return $roles;
    }

    /**
     * @param int $role
     * @param $nicename
     * @param $roletype
     * @param string $id
     * @param string $name
     * @param string $address
     *
     * @return array
     */
    private function createRoleData($role, $nicename, $roletype, $id = "", $name = "", $address = "")
    {
        return [
            'id'       => $id,
            'role'     => $role,
            'nicename' => $nicename,
            'name'     => $name,
            'address'  => $address,
            'roletype' => $roletype,
        ];
    }
}
