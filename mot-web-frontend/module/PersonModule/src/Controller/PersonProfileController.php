<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\Controller;

use Application\Data\ApiPersonalDetails;
use Application\Helper\DataMappingHelper;
use Application\Service\CatalogService;
use Core\Controller\AbstractAuthActionController;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Controller\UserHomeController;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Model\PersonalDetails;
use Dvsa\Mot\Frontend\PersonModule\Security\PersonProfileGuard;
use Dvsa\Mot\Frontend\PersonModule\Security\PersonProfileGuardBuilder;
use Dvsa\Mot\Frontend\PersonModule\ViewModel\Sidebar\ProfileSidebar;
use DvsaCommon\Constants\FeatureToggle;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\View\Model\ViewModel;

/**
 * Controller for the Person Profile page.
 */
class PersonProfileController extends AbstractAuthActionController
{
    /**
     * @var ApiPersonalDetails
     */
    private $personalDetailsService;

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
     * @var ViewTradeRolesAssertion
     */
    private $viewTradeRolesAssertion;

    /**
     * @var PersonProfileGuardBuilder
     */
    private $personProfileGuardBuilder;

    /**
     * PersonProfileController constructor.
     *
     * @param ApiPersonalDetails        $personalDetailsService
     * @param ApiDashboardResource      $dashboardResourceService
     * @param CatalogService            $catalogService
     * @param UserAdminSessionManager   $userAdminSessionManager
     * @param ViewTradeRolesAssertion   $canViewTradeRolesAssertion
     * @param PersonProfileGuardBuilder $personProfileGuardBuilder
     */
    public function __construct(ApiPersonalDetails $personalDetailsService,
                                ApiDashboardResource $dashboardResourceService,
                                CatalogService $catalogService,
                                UserAdminSessionManager $userAdminSessionManager,
                                ViewTradeRolesAssertion $canViewTradeRolesAssertion,
                                PersonProfileGuardBuilder $personProfileGuardBuilder
    ) {
        $this->personalDetailsService = $personalDetailsService;
        $this->dashboardResourceService = $dashboardResourceService;
        $this->catalogService = $catalogService;
        $this->userAdminSessionManager = $userAdminSessionManager;
        $this->viewTradeRolesAssertion = $canViewTradeRolesAssertion;
        $this->personProfileGuardBuilder = $personProfileGuardBuilder;
    }

    /**
     * @return array|\Zend\View\Model\ViewModel
     */
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

        $breadcrumbs = [
            $personDetails->getFullName() => '',
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        $personId = $this->getPersonIdFromRequest();
        $personProfileGuard = $this->personProfileGuardBuilder->createPersonProfileGuard($personDetails,
            $this->getContextFromRequest());
        $profileSidebar = $this->createProfileSidebar($personId, $personProfileGuard);
        $this->setSidebar($profileSidebar);

        return $this->createViewModel('profile/index.phtml', [
            'personalDetails'           => $personDetails,
            'isViewingOwnProfile'       => $data['isViewingOwnProfile'],
            'systemRoles'               => $this->getSystemRoles($personDetails),
            'personProfileGuard'        => $personProfileGuard,
            'userHomeRoute'             => UserHomeController::ROUTE,
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
            'systemRoles'          => $this->getSystemRoles($personalDetails),
        ];
    }

    /**
     * @return int
     */
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
     * @return string
     */
    private function getContextFromRequest()
    {
        return (string) $this->params()->fromQuery('context', PersonProfileGuard::NO_CONTEXT);
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

    /**
     * @param int                $targetPersonId
     * @param PersonProfileGuard $personProfileGuard
     *
     * @return \Dvsa\Mot\Frontend\PersonModule\ViewModel\Sidebar\ProfileSidebar
     */
    private function createProfileSidebar($targetPersonId, PersonProfileGuard $personProfileGuard)
    {
        $testerAuthorisation = $this->personProfileGuardBuilder->getTesterAuthorisation($targetPersonId);

        return new ProfileSidebar($targetPersonId, $personProfileGuard, $testerAuthorisation);
    }
}
