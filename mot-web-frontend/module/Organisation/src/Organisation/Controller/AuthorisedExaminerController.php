<?php

namespace Organisation\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;
use Organisation\Form\AeContactDetailsForm;
use Organisation\Form\AeCreateForm;
use Organisation\Presenter\AuthorisedExaminerPresenter;
use Organisation\ViewModel\AuthorisedExaminer\AeFormViewModel;
use Organisation\ViewModel\View\Index\IndexViewModel;
use Zend\Http\Request;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AuthorisedExaminerController
 *
 * @package Organisation\Controller
 */
class AuthorisedExaminerController extends AbstractDvsaMotTestController
{
    const SESSION_CNTR_KEY = 'AE_CREATE_UPDATE';
    const SESSION_KEY = 'data';

    const AE_SUBTITLE = 'AE management';
    const INDEX_TITLE_FULL = 'Full Details of Authorised Examiner';
    const INDEX_TITLE = 'Authorised Examiner';

    const CREATE_TITLE = 'Create Authorised Examiner';
    const CREATE_CONFIRM_TITLE = 'Confirm new AE details';

    const EDIT_TITLE = 'Change contact details';
    const EDIT_SUBTITLE = 'Authorised examiner';

    const FORM_ERROR = 'Unable to find Authorised Examiner';
    const ERR_MSG_INVALID_AE_ID = 'No Authorised Examiner Id provided';

    const STEP_ONE = 'Step 1 of 2';
    const STEP_TWO = 'Step 2 of 2';

    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    private $auth;
    /**
     * @var MapperFactory
     */
    private $mapper;
    /**
     * @var MotIdentityProviderInterface
     */
    private $identity;
    /**
     * @var Container
     */
    private $session;

    /**
     * @param MotFrontendAuthorisationServiceInterface $auth
     * @param MapperFactory                            $mapper
     * @param MotIdentityProviderInterface             $identity
     * @param Container                                $session
     */
    public function __construct(
        MotFrontendAuthorisationServiceInterface $auth,
        MapperFactory $mapper,
        MotIdentityProviderInterface $identity,
        Container $session
    ) {
        $this->auth = $auth;
        $this->mapper = $mapper;
        $this->identity = $identity;
        $this->session = $session;
    }

    public function indexAction()
    {
        $orgId = $this->params('id');
        if (!$this->auth->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $this->auth->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_READ, $orgId);
        }

        $this->layout("layout/layout-govuk.phtml");

        $vm = $this->getIndexViewModel($orgId);
        $presenter = new AuthorisedExaminerPresenter($vm->getOrganisation());

        /** @var \DvsaCommon\Dto\AreaOffice\AreaOfficeDto $aoDto */
        $aoDto = $vm->getOrganisation()->getAuthorisedExaminerAuthorisation()->getAssignedAreaOffice();
        $aoDetailsUrl = '#';
        $aoLabel = '';

        if ($aoDto) {
            $aoId = $aoDto->getSiteId();
            $aoNumber = $aoDto->getAoNumber();

            if ($aoId) {
                $aoDetailsUrl = VehicleTestingStationUrlBuilderWeb::byId(1);
            }
            if ($aoNumber) {
                $aoLabel = $aoNumber;
            }
        }

        //  logical block :: prepare view model

        $viewModel = new ViewModel(
            [
                'viewModel' => $vm,
                'presenter' => $presenter,
                'organisation' => $vm->getOrganisation(),
                'organisationId' => $orgId,
                'urlBack' => $this->getBackButton($orgId),
                'slotsButton' => $this->auth->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AE_SLOTS_USAGE_READ,
                    $orgId
                ),
                'canSettlePayment' => $this->auth->isGranted(PermissionAtOrganisation::SLOTS_INSTANT_SETTLEMENT),
                'canSetDirectDebit' => $this->auth->isGrantedAtOrganisation(
                    PermissionAtOrganisation::SLOTS_PAYMENT_DIRECT_DEBIT, $orgId
                ),
                'canAdjust' => $this->auth->isGrantedAtOrganisation(
                    PermissionAtOrganisation::SLOTS_ADJUSTMENT, $orgId
                ),
                'testLogButton' => $this->auth->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AE_TEST_LOG, $orgId
                ),
                'eventButton' => $this->auth->isGranted(PermissionInSystem::LIST_EVENT_HISTORY),

                'aoOfficeLabel' => $aoLabel,
                'aoOfficeUrl' => $aoDetailsUrl,
            ]
        );

        $addressInLine = AddressUtils::stringify(
            $vm->getOrganisation()->getRegisteredCompanyContactDetail()->getAddress()
        );

        if ($this->auth->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $title = self::INDEX_TITLE_FULL;
        } else {
            $title = self::INDEX_TITLE;
        }
        $this->layout()->setVariable('pageTertiaryTitle', $addressInLine);

        return $this->prepareViewModel($viewModel, $vm->getOrganisation()->getName(), $title);
    }

    private function getBackButton($orgId)
    {
        if ($this->getAuthorisationForView($orgId)->canSearchAe()) {
            $backTo = 'listAE';
            $uri = AuthorisedExaminerUrlBuilderWeb::of()->aeSearch()->toString();
        } elseif ($this->getAuthorisationForView($orgId)->canSearchUser()) {
            $backTo = 'listUser';
            $uri = UserAdminUrlBuilderWeb::of()->userSearch()->toString();
        } else {
            $backTo = 'home';
            $uri = PersonUrlBuilderWeb::home()->toString();
        }

        return [
            'type' => $backTo,
            'uri' => $uri,
        ];
    }

    private function getIndexViewModel($orgId)
    {
        $viewAuthorisation = $this->getAuthorisationForView($orgId);

        $org = $this->mapper->Organisation->getAuthorisedExaminer($orgId);

        $positions = $viewAuthorisation->canViewPersonnel()
            ? $this->mapper->OrganisationPosition->fetchAllPositionsForOrganisation($org->getId())
            : [];
        $viewAuthorisation->setPositions($positions);

        $principals = $viewAuthorisation->canViewAuthorisedExaminerPrincipals()
            ? $this->mapper->Person->fetchPrincipalsForOrganisation($org->getId())
            : [];

        $vehicleTestingStations = $viewAuthorisation->canViewVtsList()
            ? $this->mapper->OrganisationSites->fetchAllForOrganisation($org->getId())
            : [];

        return new IndexViewModel($viewAuthorisation, $org, $vehicleTestingStations, $positions, $principals);
    }

    /**
     * @param int $orgId
     * @return AuthorisedExaminerViewAuthorisation
     */
    private function getAuthorisationForView($orgId)
    {
        return new AuthorisedExaminerViewAuthorisation($this->auth, $this->identity, $orgId);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     * @throws \Exception
     */
    public function editAction()
    {
        $orgId = $this->params('id');

        $this->auth->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $orgId);

        $aeViewUrl = AuthorisedExaminerUrlBuilderWeb::of($orgId)->toString();
        $organisation = $this->mapper->Organisation->getAuthorisedExaminer($orgId);

        //  logical block :: prepare model
        $form = new AeContactDetailsForm($organisation);
        $form->setCancelUrl($aeViewUrl);

        /* @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->fromPost($request->getPost());

            if ($form->isValid()) {
                $aeDto = $form->toDto();

                try {
                    $this->mapper->Organisation->update($orgId, $aeDto);

                    return $this->redirect()->toUrl($aeViewUrl);
                } catch (RestApplicationException $e) {
                    $this->addErrorMessages($e->getDisplayMessages());
                }
            }
        }

        //  logical block :: prepare view
        $subTitle = self::EDIT_SUBTITLE . ' - ' .
            $organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef();

        $breadcrumbs = [$organisation->getName() => $aeViewUrl];

        return $this->prepareViewModel(
            new ViewModel(['model' => $form]), self::EDIT_TITLE, $subTitle, $breadcrumbs
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     */
    public function createAction()
    {
        $this->auth->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_CREATE);

        /** @var Request $request */
        $request = $this->getRequest();

        //  create new form or get from session when come back from confirmation
        $sessionKey = $request->getQuery(self::SESSION_KEY) ?: uniqid();
        $form = $this->session->offsetGet($sessionKey);

        if (!$form instanceof AeCreateForm) {
            $form = new AeCreateForm();
            $form->setCompanyTypes($this->getCompanyTypes());
            $form->setAreaOfficeOptions($this->getAreaOfficeOptions(true));
        }
        $form->setFormUrl(AuthorisedExaminerUrlBuilderWeb::create()->queryParam(self::SESSION_KEY, $sessionKey));

        if ($request->isPost()) {
            $form->fromPost($request->getPost());

            try {
                $this->mapper->Organisation->validate($form->toDto());

                $this->session->offsetSet($sessionKey, $form);

                $url = AuthorisedExaminerUrlBuilderWeb::createConfirm()
                    ->queryParam(self::SESSION_KEY, $sessionKey);

                return $this->redirect()->toUrl($url);

            } catch (RestApplicationException $ve) {
                $form->addErrorsFromApi($ve->getErrors());
            }
        }

        //  create a model
        $model = new AeFormViewModel();
        $model
            ->setForm($form)
            ->setCancelUrl('/');

        return $this->prepareViewModel(
            new ViewModel(['model' => $model]), self::CREATE_TITLE, self::AE_SUBTITLE, null, self::STEP_ONE
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     */
    public function confirmationAction()
    {
        $this->auth->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_CREATE);

        $urlCreate = AuthorisedExaminerUrlBuilderWeb::create();

        /** @var Request $request */
        $request = $this->getRequest();

        //  get form from session
        $sessionKey = $request->getQuery(self::SESSION_KEY);
        $form = $this->session->offsetGet($sessionKey);

        //  redirect to create ae page if form data not provided
        if (!($form instanceof AeCreateForm)) {
            return $this->redirect()->toUrl($urlCreate);
        }

        //  save ae to db and redirect to ae view page
        if ($request->isPost()) {
            try {
                $result = $this->mapper->Organisation->create($form->toDto());

                //  clean session after self
                $this->session->offsetUnset($sessionKey);

                return $this->redirect()->toUrl(AuthorisedExaminerUrlBuilderWeb::of($result['id']));
            } catch (RestApplicationException $ve) {
                $this->addErrorMessages($ve->getDisplayMessages());
            }
        }

        //  create a model
        $model = new AeFormViewModel();
        $model
            ->setForm($form)
            ->setCancelUrl($urlCreate->queryParam(self::SESSION_KEY, $sessionKey));

        $form->setFormUrl(
            AuthorisedExaminerUrlBuilderWeb::createConfirm()
                ->queryParam(self::SESSION_KEY, $sessionKey)
        );

        $viewModel = $this->prepareViewModel(
            new ViewModel(['model' => $model]), self::CREATE_CONFIRM_TITLE, self::AE_SUBTITLE, null, self::STEP_TWO
        );

        $viewModel->setVariable(
            'areaOfficeDisplayName',
            $form->getAssignedAreaOffice()
        );
        return $viewModel;
    }

    private function getCompanyTypes()
    {
        $companyTypes = array_combine(CompanyTypeCode::getAll(), CompanyTypeName::getAll());
        unset($companyTypes[CompanyTypeCode::LIMITED_LIABILITY_PARTNERSHIP]);

        return ArrayUtils::asortBy($companyTypes);
    }


    /**
     * Asks the API for the list of Area Offices that a user can select
     * to be associated as the controlling AO for this AE entity.
     *
     * @return array|mixed
     */
    protected function getAreaOfficeOptions()
    {
        try {
            return $this->mapper->Organisation->getAllAreaOffices(true);
        } catch (RestApplicationException $ve) {
            $this->addErrorMessages($ve->getDisplayMessages());
        }
        return [];
    }

    /**
     * Given a well-formed string, return the Area Office number, given that the
     * strings begins with two digits and that the extracted number is one of those
     * returned from a call to self::getAreaOfficeOptions().
     *
     * @param $aoName string contains the label we want to decode
     * @param $aoOptions array contains all valid AO numbers
     *
     * @return int for the area office, -1 indicates a problem with the source name
     */
    public static function getAONumberFromName($aoName, $aoOptions)
    {
        $result = -1;

        if (strlen($aoName) > 1) {
            $number = substr($aoName, 0, 2);

            if (ctype_digit($number)) {
                foreach($aoOptions as $areaOffice) {
                    if ($number == $areaOffice['areaOfficeNumber']) {
                        $result = (int)$number;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Prepare the view model for all the step of the create ae
     *
     * @param ViewModel $view
     * @param string $title
     * @param string $subtitle
     * @param null $breadcrumbs
     * @param array $progress
     * @param string $template
     *
     * @return ViewModel
     */
    private function prepareViewModel(
        ViewModel $view,
        $title,
        $subtitle,
        $breadcrumbs = null,
        $progress = null,
        $template = null
    ) {
        //  logical block:: prepare view
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageTitle', $title);
        $this->layout()->setVariable('pageSubTitle', $subtitle);

        if ($progress !== null) {
            $this->layout()->setVariable('progress', $progress);
        }

        $breadcrumbs = (is_array($breadcrumbs) ? $breadcrumbs : []) + [$title => ''];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        return $template !== null ? $view->setTemplate($template) : $view;
    }
}
