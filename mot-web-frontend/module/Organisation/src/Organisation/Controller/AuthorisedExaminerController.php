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
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;
use Organisation\Form\AeContactDetailsForm;
use Organisation\Form\AeCreateForm;
use Organisation\Presenter\AuthorisedExaminerPresenter;
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
    const INDEX_TITLE_FULL = 'Full Details of Authorised Examiner';
    const INDEX_TITLE = 'Authorised Examiner';
    const CREATE_TITLE = 'Create Authorised Examiner';
    const EDIT_TITLE = 'Change contact details';
    const EDIT_SUBTITLE = 'Authorised examiner';

    const FORM_ERROR = 'Unable to find Authorised Examiner';
    const ERR_MSG_INVALID_AE_ID = 'No Authorised Examiner Id provided';

    const ROUTE_INDEX = 'authorised-examiner';

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
     * @param MotFrontendAuthorisationServiceInterface $auth
     * @param MapperFactory $mapper
     * @param MotIdentityProviderInterface $identity
     */
    public function __construct(
        MotFrontendAuthorisationServiceInterface $auth,
        MapperFactory $mapper,
        MotIdentityProviderInterface $identity
    ) {
        $this->auth = $auth;
        $this->mapper = $mapper;
        $this->identity = $identity;
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

        //  --  view model  --
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
            ]
        );

        $addressInLine = htmlentities(
            AddressUtils::stringify(
                $vm->getOrganisation()->getRegisteredCompanyContactDetail()->getAddress()
            )
        );

        if ($this->auth->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $title = self::INDEX_TITLE_FULL;
        } else {
            $title = self::INDEX_TITLE;
        }
        $this->layout()->setVariable('pageSubTitle', $title);
        $this->layout()->setVariable('pageTitle', $vm->getOrganisation()->getName());
        $this->layout()->setVariable('pageTertiaryTitle', $addressInLine);

        return $viewModel;
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
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable(
            'pageSubTitle',
            self::EDIT_SUBTITLE . ' - ' .
            $organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef()
        );
        $this->layout()->setVariable('pageTitle', self::EDIT_TITLE);

        $breadcrumbs = [
            $organisation->getName() => $aeViewUrl,
            self::EDIT_TITLE => '',
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        return new ViewModel(['form' => $form]);
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     */
    public function createAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::AO1_AE_CREATE);

        $this->auth->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_CREATE);

        //  logical block :: init view form model
        //  remove LIMITED_LIABILITY_PARTNERSHIP from available options;
        $companyTypes = array_combine(CompanyTypeCode::getAll(), CompanyTypeName::getAll());
        unset($companyTypes[CompanyTypeCode::LIMITED_LIABILITY_PARTNERSHIP]);

        //  create a form model
        $form = new AeCreateForm();
        $form
            ->setCancelUrl('/')
            ->setCompanyTypes($companyTypes);

        /* @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->fromPost($request->getPost());

            if ($form->isValid(true)) {
                $aeDto = $form->toDto();

                try {
                    $result = $this->mapper->Organisation->create($aeDto);
                    return $this->redirect()->toUrl(AuthorisedExaminerUrlBuilderWeb::of($result['id']));
                } catch (ValidationException $ve) {
                    $this->addErrorMessages($ve->getDisplayMessages());
                }
            }
        }

        //  logical block:: prepare view
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageTitle', self::CREATE_TITLE);

        $breadcrumbs = [self::CREATE_TITLE => ''];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        return new ViewModel(['form' => $form]);
    }
}
