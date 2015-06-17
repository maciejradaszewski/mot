<?php

namespace Site\Controller;

use Core\Controller\AbstractAuthActionController;
use DataCatalogApi\Service\DataCatalogService;
use Dashboard\Controller\UserHomeController;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\Role;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use Site\Authorization\VtsOverviewPagePermissions;
use Site\Form\VehicleTestingStationForm;
use Site\Form\VtsContactDetailsUpdateForm;
use Site\Traits\SiteServicesTrait;
use Site\ViewModel\VTSDecorator;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Site\Presenter\VtsPresenter;

/**
 * Class VehicleTestingStationController
 *
 * @package DvsaMotTest\Controller
 */
class VehicleTestingStationController extends AbstractAuthActionController
{
    use SiteServicesTrait;

    const REFERER = 'refererToSite';

    const EDIT_TITLE = 'Change contact details';
    const EDIT_SUBTITLE = 'Vehicle testing station';

    const ROUTE_CREATE = 'site/create';
    const ROUTE_CONFIGURE_BRAKE_TEST_DEFAULTS = 'site/configure-brake-test-defaults';

    const FORM_ERROR = 'Unable to find VTS';
    const CHANGE_BRAKE_TEST_DEFAULTS_AUTHORISATION_ERROR = "You are not authorised to change the brake test defaults";
    const SEARCH_RESULT_PARAM = 'q';

    const ERR_MSG_INVALID_SITE_ID_OR_NR = 'No Id or Site Number provided';

    /**
     * Display the details of a VTS
     */
    public function indexAction()
    {
        $isEnforcementUser = $this->getAuthorizationService()->hasRole(Role::VEHICLE_EXAMINER);
        $isSiteManagerUser =
            $this->getAuthorizationService()->hasRole(
                OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER
            )
            || $this->getAuthorizationService()->hasRole(SiteBusinessRoleCode::SITE_MANAGER);

        //  --  process request --
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $vtsId = $this->params()->fromRoute('id', null);
        $siteNumber = $this->params()->fromRoute('sitenumber', null);
        $vtsData = [];

        //  --  store url for back url in following pages   --
        $refBack = new Container(self::REFERER);
        $refBack->uri = $request->getUri();

        //  --  request site data from service  --
        $mapperFactory = $this->getMapperFactory();
        try {
            if (isset($vtsId)) {
                $vtsData = $mapperFactory->VehicleTestingStation->getById($vtsId);
            } elseif (isset($siteNumber)) {
                $vtsData = $mapperFactory->VehicleTestingStation->getBySiteNumber($siteNumber);
            } else {
                throw new \Exception(self::ERR_MSG_INVALID_SITE_ID_OR_NR);
            }
        } catch (ValidationException $e) {
            $this->addErrorMessages(self::FORM_ERROR);
        }

        $vtsId = $vtsData['id'];

        $permissions = $this->getPermissions($vtsData);

        //  --  prepare view data   --
        $equipment = $mapperFactory->Equipment->fetchAllForVts($vtsId);
        $testInProgress = ($permissions->canViewTestsInProgress()
            ? $mapperFactory->MotTestInProgress->fetchAllForVts($vtsId)
            : []
        );

        $equipmentModelStatusMap = $this->getCatalogService()->getEquipmentModelStatuses();
        $decorator = new VTSDecorator($vtsData, $equipment, $testInProgress, $permissions, $equipmentModelStatusMap);

        //  --  get ref page    --
        $refSession = new Container('referralSession');
        if ($isEnforcementUser && !empty($refSession->url)) {
            $escRefPage = '/mot-test-search/vrm?' . http_build_query($refSession->url);
        } else {
            $escRefPage = null;
        }
        $refSession->url = false;

        //  --
        $searchString = null;
        if ($isEnforcementUser) {
            // Used when constructing the back-link.  If the searchString is provided we know
            // that the previous page was a VE search result page.  We can re-create the query from
            // the search string param; otherwise we default to the VE search page.
            $searchString = $request->getQuery(self::SEARCH_RESULT_PARAM);
        }

        $presenter = new VtsPresenter($vtsData, $this->getServiceLocator()->get('CatalogService'), $this->serviceLocator->get("AuthorisationService"));

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle', 'Vehicle Testing Station');
        $this->layout()->setVariable('pageTitle', $decorator['name']);
        $this->layout()->setVariable('pageTertiaryTitle', $presenter->getFullAddress());

        return new ViewModel(
            [
                'presenter'         => $presenter,
                'siteDetails'       => $decorator,
                'searchString'      => $searchString,
                'escRefPage'        => $escRefPage,
            ]
        );
    }

    public function createAction()
    {
        $updateVtsAssertion = $this->getUpdateVtsAssertion();

        $form = new VehicleTestingStationForm();
        $form->setUpdateVtsAssertion($updateVtsAssertion);


        $formAction = $this->url()->fromRoute('site/create');
        $cancelUrl = $this->url()->fromRoute(UserHomeController::ROUTE);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->populateFromInput($request->getPost()->toArray());
            if ($form->isValid()) {
                $data = $form->toApiData();

                try {
                    $id = $this->getMapperFactory()->VehicleTestingStation->create($data);
                    return $this->redirect()->toUrl(VehicleTestingStationUrlBuilderWeb::byId($id));
                } catch (ValidationException $exception) {
                    $this->addErrorMessages($exception->getDisplayMessages());
                }
            }
        }

        $viewModel = new ViewModel(
            [
                'form'       => $form,
                'vtsData'    => [],
                'formAction' => $formAction,
                'cancelUrl'  => $cancelUrl,
            ]
        );

        // Single view form for edit and create
        $viewModel->setTemplate('site/vehicle-testing-station/edit.phtml');

        return $viewModel;
    }

    /**
     * @deprecated VM-7285    Should be removed, but it was not requested in story requirements
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');

        //  --  check permission  --
        $this->getUpdateVtsAssertion()->assertGranted($id);

        $mapperFactory = $this->getMapperFactory();

        $vtsData = $mapperFactory->VehicleTestingStation->getById($id);

        $updateVtsAssertion = $this->getUpdateVtsAssertion();
        $updateVtsAssertion->assertGranted($id);

        $form = new VehicleTestingStationForm();
        $form->setUpdateVtsAssertion($updateVtsAssertion);
        $form->setVtsId($id);

        $formAction = $this->url()->fromRoute('vehicle-testing-station-edit', ['id' => $id]);
        $cancelUrl = $this->url()->fromRoute('vehicle-testing-station', ['id' => $vtsData['id']]);

        $form->populateFromApi($vtsData);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->populateFromInput($request->getPost()->toArray());

            if ($form->isValid()) {
                $data = $form->toApiData();

                try {
                    $mapperFactory->VehicleTestingStation->update($id, $data);
                    return $this->redirect()->toUrl(VehicleTestingStationUrlBuilderWeb::byId($id));
                } catch (ValidationException $exception) {
                    $this->addErrorMessages($exception->getDisplayMessages());
                }
            }
        }

        $viewModel = new ViewModel(
            [
                'form'       => $form,
                'vtsData'    => $vtsData,
                'formAction' => $formAction,
                'cancelUrl'  => $cancelUrl,
            ]
        );

        return $viewModel;
    }

    public function contactDetailsAction()
    {
        $siteId = $this->params('id');
        if ((int)$siteId == 0) {
            throw new \Exception(self::ERR_MSG_INVALID_SITE_ID_OR_NR);
        }

        //  --  check permission  --
        $this->getUpdateVtsAssertion()->assertGranted($siteId);

        //  --  request site data from api  --
        $mapperFactory = $this->getMapperFactory();
        $vtsDto = $mapperFactory->VehicleTestingStationDto->getById($siteId);

        //  --  form model  --
        $form = new VtsContactDetailsUpdateForm();
        $form->populateFromApi($vtsDto);

        $vtsViewUrl = VehicleTestingStationUrlBuilderWeb::byId($siteId)->toString();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->populateFromPost($request->getPost()->toArray());

            if ($form->isValid()) {
                $contactDto = $form->toApiData();

                try {
                    $mapperFactory->VehicleTestingStationDto->updateContactDetails($siteId, $contactDto);

                    return $this->redirect()->toUrl($vtsViewUrl);
                } catch (ValidationException $ve) {
                    $form->addErrors($ve->getErrors());
                }
            }
        }

        $viewModel = new ViewModel(
            [
                'form'      => $form,
                'cancelUrl' => $vtsViewUrl,
            ]
        );

        $breadcrumbs = [
            $form->getDto()->getName() => $vtsViewUrl,
            self::EDIT_TITLE           => '',
        ];

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable(
            'pageSubTitle',
            self::EDIT_SUBTITLE . ' - ' . $form->getDto()->getSiteNumber()
        );
        $this->layout()->setVariable('pageTitle', self::EDIT_TITLE);
        $this->layout()->setVariable('progressBar', ['breadcrumbs' => $breadcrumbs]);

        return $viewModel;
    }

    public function configureBrakeTestDefaultsAction()
    {
        $id = (int)$this->params()->fromRoute('id');

        if ($id <= 0) {
            throw new \Exception(self::ERR_MSG_INVALID_SITE_ID_OR_NR);
        }

        // TODO @RBAC - ensure working when openam is introduced
        $this->getAuthorizationService()->assertGrantedAtSite(PermissionAtSite::DEFAULT_BRAKE_TESTS_CHANGE, $id);

        $mapperFactory = $this->getMapperFactory();
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();

            $mapperFactory->VehicleTestingStation->saveDefaultBrakeTests($id, $postData);

            return $this->redirect()->toUrl(VehicleTestingStationUrlBuilderWeb::byId($id));
        }

        $vtsData = $mapperFactory->VehicleTestingStation->getById($id);

        $vtsPageUrl = VehicleTestingStationUrlBuilderWeb::byId($id);

        return [
            'defaultBrakeTestClass1And2'            => $vtsData['defaultBrakeTestClass1And2'],
            'defaultParkingBrakeTestClass3AndAbove' => $vtsData['defaultParkingBrakeTestClass3AndAbove'],
            'defaultServiceBrakeTestClass3AndAbove' => $vtsData['defaultServiceBrakeTestClass3AndAbove'],
            'cancelRoute'                           => $vtsPageUrl,
            'canTestClass1Or2'                      => $this->getPermissions($vtsData)->canTestClass1And2(),
            'canTestAnyOfClass3AndAbove'            => $this->getPermissions($vtsData)->canTestAnyOfClass3AndAbove(),
            'brakeTestTypes'                        => $this->getCatalogService()->getBrakeTestTypes(),
        ];
    }

    /**
     * @param array $vtsData
     * @return VtsOverviewPagePermissions
     */
    private function getPermissions($vtsData)
    {
        $permissions = new VtsOverviewPagePermissions(
            $this->getAuthorizationService(),
            $this->getIdentity(),
            $vtsData,
            $vtsData['positions'],
            ArrayUtils::tryGet($vtsData, 'organisation') ? $vtsData['organisation']['id'] : ''
        );

        return $permissions;
    }

    /**
     * @return UpdateVtsAssertion
     */
    private function getUpdateVtsAssertion()
    {
        return new UpdateVtsAssertion($this->getAuthorizationService());
    }
}
