<?php

namespace Organisation\Controller;

use Core\Controller\AbstractAuthActionController;
use Dashboard\Controller\UserHomeController;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\OrganisationType;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;
use Organisation\Form\AeCreateForm;
use Organisation\Presenter\AuthorisedExaminerPresenter;
use Organisation\Traits\OrganisationServicesTrait;
use Organisation\ViewModel\View\Index\IndexViewModel;
use Zend\Http\Request;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class ViewController
 *
 * @package Organisation\Controller
 */
class ViewController extends AbstractAuthActionController
{
    use OrganisationServicesTrait;

    const CREATE_TITLE = 'Create Authorised Examiner';
    const EDIT_TITLE = 'Change contact details';
    const EDIT_SUBTITLE = 'Authorised examiner';

    const FORM_ERROR = 'Unable to find Authorised Examiner';
    const ERR_MSG_INVALID_AE_ID = 'No Authorised Examiner Id provided';

    const ROUTE_INDEX = 'authorised-examiner';

    public function indexAction()
    {
        $this->layout("layout/layout-govuk.phtml");

        $authService = $this->getAuthorizationService();

        $orgId = $this->params('id');
        if ((int)$orgId == 0) {
            throw new \Exception(self::ERR_MSG_INVALID_AE_ID);
        }

        if (!$authService->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_READ, $orgId);
        }

        $vm = $this->getIndexViewModel($orgId);
        $presenter = new AuthorisedExaminerPresenter($vm->getOrganisation());

        //  --  view model  --
        $viewModel = new ViewModel(
            [
                'viewModel' => $vm,
                'presenter' => $presenter,
                'organisation' => $vm->getOrganisation(),
                'organisationId' => $vm->getOrganisation()->getId(),
                'urlBack' => $this->getBackButton(),
                'slotsButton' => $authService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AE_SLOTS_USAGE_READ, $orgId
                ),
                'canSettlePayment' => $authService->isGranted(PermissionAtOrganisation::SLOTS_INSTANT_SETTLEMENT),
                'canSetDirectDebit' => $authService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::SLOTS_PAYMENT_DIRECT_DEBIT, $orgId
                ),
                'canAdjust' => $authService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::SLOTS_ADJUSTMENT, $orgId
                ),
                'testLogButton' => $authService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AE_TEST_LOG, $orgId
                ),
                'eventButton' => $authService->isGranted(PermissionInSystem::LIST_EVENT_HISTORY),
            ]
        );

        if ($authService->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $title = 'Full Details of Authorised Examiner';
        } else {
            $title = 'Authorised Examiner';
        }

        $addressInLine = AddressUtils::stringify(
            $vm->getOrganisation()
                ->getRegisteredCompanyContactDetail()
                ->getAddress()
        );

        $this->layout()->setVariable('pageSubTitle', $title);
        $this->layout()->setVariable('pageTitle', $vm->getOrganisation()->getName());
        $this->layout()->setVariable('pageTertiaryTitle', $addressInLine);

        return $viewModel;
    }

    private function getBackButton()
    {
        if ($this->getAuthorisationForView()->canSearchAE()) {
            $backTo = 'listAE';
            $uri = AuthorisedExaminerUrlBuilderWeb::of()->aeSearch()->toString();
        } elseif($this->getAuthorisationForView()->canSearchUser()) {
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
        $org = $vehicleTestingStations = $positions = $principals = null;
        $mapperFactory = $this->getMapperFactory();

        try {
            $org = $mapperFactory->Organisation->getAuthorisedExaminer($orgId);
            $viewAuthorisation = $this->getAuthorisationForView();

            $positions = $viewAuthorisation->canViewPersonnel()
                ? $mapperFactory->OrganisationPosition->fetchAllPositionsForOrganisation($org->getId())
                : [];

            $viewAuthorisation->setPositions($positions);

            $principals = $viewAuthorisation->canViewAuthorisedExaminerPrincipals()
                ? $mapperFactory->Person->fetchPrincipalsForOrganisation($org->getId())
                : [];
            $vehicleTestingStations = $viewAuthorisation->canViewVtsList()
                ? $mapperFactory->OrganisationSites->fetchAllForOrganisation($org->getId())
                : [];

        } catch (ValidationException $e) {
            $this->addErrorMessages(self::FORM_ERROR);
        }

        return new IndexViewModel($viewAuthorisation, $org, $vehicleTestingStations, $positions, $principals);
    }

    /**
     * @return AuthorisedExaminerViewAuthorisation
     */
    private function getAuthorisationForView()
    {
        $authorisationService = $this->getAuthorizationService();
        $authorisedExaminerId = $this->params('id');

        return new AuthorisedExaminerViewAuthorisation(
            $authorisationService, $this->getIdentityProvider(), $authorisedExaminerId
        );
    }

    /**
     * @return MotIdentityProviderInterface
     */
    private function getIdentityProvider()
    {
        return $this->getServiceLocator()->get('MotIdentityProvider');
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     * @throws \Exception
     */
    public function editAction()
    {
        $orgId = $this->params('id', 0);

        if (!$this->getAuthorizationService()
            ->isGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $orgId)
        ) {
            return $this->redirect()->toRoute(UserHomeController::ROUTE);
        }

        if ((int)$orgId == 0) {
            throw new \Exception(self::ERR_MSG_INVALID_AE_ID);
        }

        $mapperFactory = $this->getMapperFactory();
        $organisation = $mapperFactory->Organisation->getAuthorisedExaminer($orgId);

        return $this->processEditCall(new AeCreateForm($organisation), $organisation, $mapperFactory);
    }

    /**
     * @param AeCreateForm $form
     * @param OrganisationDto $organisation
     * @param MapperFactory $mapperFactory
     * @return \Zend\Http\Response|ViewModel
     */
    private function processEditCall(AeCreateForm $form, OrganisationDto $organisation, MapperFactory $mapperFactory)
    {
        $orgId = $organisation->getId();
        $aeViewUrl = AuthorisedExaminerUrlBuilderWeb::of($orgId)->toString();

        /* @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $aeDto = $form->populateToDto($request->getPost()->toArray());

            if ($form->isValid()) {
                try {
                    $mapperFactory->Organisation->updateAuthorisedExaminer($orgId, $aeDto);

                    return $this->redirect()->toUrl($aeViewUrl);
                } catch (ValidationException $ve) {
                    $form->addErrors($ve->getErrors());
                }
            }
        }

        $viewModel = new ViewModel(
            [
                'form' => $form,
                'cancelRoute' => $aeViewUrl,
            ]
        );

        $breadcrumbs = [
            $organisation->getName() => $aeViewUrl,
            self::EDIT_TITLE => '',
        ];

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable(
            'pageSubTitle',
            self::EDIT_SUBTITLE . ' - ' .
            $organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef()
        );
        $this->layout()->setVariable('pageTitle', self::EDIT_TITLE);
        $this->layout()->setVariable('progressBar', ['breadcrumbs' => $breadcrumbs]);

        return $viewModel;
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \DvsaCommon\Auth\NotLoggedInException
     */
    public function createAction()
    {
        if (!$this->getAuthorizationService()->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_CREATE)) {
            return $this->redirect()->toRoute(UserHomeController::ROUTE);
        }

        return $this->processCreateCall(new AeCreateForm());
    }

    /**
     * @param AeCreateForm $form
     * @return \Zend\Http\Response|ViewModel
     */
    private function processCreateCall(AeCreateForm $form)
    {
        /* @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $form->populate($postData);

            try {
                $result = $this->getRestClient()->postJson(
                    AuthorisedExaminerUrlBuilder::of(),
                    $form->toArray()
                );
                return $this->redirect()->toUrl(AuthorisedExaminerUrlBuilderWeb::of($result['data']['id']));
            } catch (ValidationException $ve) {
                $this->addErrorMessages($ve->getDisplayMessages());
            }
        }

        $viewModel = new ViewModel(
            [
                'form' => $form,
                'organisationType' => array_combine(OrganisationType::getValues(), OrganisationType::getValues()),
                'companyType' => array_combine(CompanyTypeName::getAll(), CompanyTypeName::getAll()),
                'formRoute' => AuthorisedExaminerUrlBuilderWeb::of()->aeCreate(),
                'cancelRoute' => $this->url()->fromRoute(UserHomeController::ROUTE),
                'title' => self::CREATE_TITLE
            ]
        );

        return $viewModel;
    }
}
