<?php

namespace Organisation\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\AddressUtils;
use DvsaCommon\Utility\ArrayUtils;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;
use Organisation\Form\AeContactDetailsForm;
use Organisation\Form\AeCreateForm;
use Organisation\Form\AeStatusForm;
use Organisation\Presenter\AuthorisedExaminerPresenter;
use Organisation\ViewModel\AuthorisedExaminer\AeFormStatusViewModel;
use Organisation\ViewModel\AuthorisedExaminer\AeFormViewModel;
use Organisation\ViewModel\View\Index\IndexViewModel;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AuthorisedExaminerStatusController
 *
 * @package Organisation\Controller
 */
class AuthorisedExaminerStatusController extends AbstractDvsaMotTestController
{
    const SESSION_CNTR_KEY = 'AE_EDIT_STATUS';
    const SESSION_KEY = 'data';

    const EDIT_SUBTITLE = 'Authorised examiner - %s';
    const EDIT_TITLE = 'Change AE details';
    const CONFIRM_TITLE = 'Confirm AE details';

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
     * @param MapperFactory $mapper
     * @param MotIdentityProviderInterface $identity
     * @param Container $session
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

    /**
     * This action display the change status form and check if the form is valid (api)
     *
     * @return \Zend\Http\Response|ViewModel|Response
     */
    public function indexAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::AO1_AE_EDIT_STATUS);
        $this->auth->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_STATUS_UPDATE);

        $orgId = $this->params('id');

        /** @var Request $request */
        $request = $this->getRequest();

        $sessionKey     = $request->getQuery(self::SESSION_KEY) ?: uniqid();
        $aeViewUrl      = AuthorisedExaminerUrlBuilderWeb::of($orgId)->toString();
        $organisation   = $this->mapper->Organisation->getAuthorisedExaminer($orgId);
        $form           = $this->session->offsetGet($sessionKey);

        //  create/get a form
        if (!$form instanceof AeStatusForm) {
            $form = new AeStatusForm();
        }
        $form->setFormUrl(
            AuthorisedExaminerUrlBuilderWeb::aeEditStatus($orgId)->queryParam(self::SESSION_KEY, $sessionKey)
        );

        if ($request->isPost()) {
            $form->fromPost($request->getPost());

            try {
                $this->mapper->Organisation->validateStatus($form->toDto(), $orgId);

                $this->session->offsetSet($sessionKey, $form);

                $url = AuthorisedExaminerUrlBuilderWeb::aeEditStatusConfirm($orgId)
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
            ->setCancelUrl($aeViewUrl);

        //  prepare view
        $subTitle = sprintf(
            self::EDIT_SUBTITLE,
            $organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef()
        );
        $breadcrumbs = [$organisation->getName() => $aeViewUrl];

        return $this->prepareViewModel(
            new ViewModel(['model' => $model]), self::EDIT_TITLE, $subTitle, '', $breadcrumbs, self::STEP_ONE
        );
    }

    /**
     * This action display the confirmation of the status and check/save the new status
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function confirmationAction()
    {
        $this->assertFeatureEnabled(FeatureToggle::AO1_AE_EDIT_STATUS);
        $this->auth->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_STATUS_UPDATE);

        $orgId = $this->params('id');

        $request = $this->getRequest();

        $sessionKey     = $request->getQuery(self::SESSION_KEY) ?: uniqid();
        $aeViewUrl      = AuthorisedExaminerUrlBuilderWeb::of($orgId)->toString();
        $organisation   = $this->mapper->Organisation->getAuthorisedExaminer($orgId);
        $form           = $this->session->offsetGet($sessionKey);

        //  redirect to edit status page if form data not provided
        if (!($form instanceof AeStatusForm)) {
            return $this->redirect()->toUrl(AuthorisedExaminerUrlBuilderWeb::aeEditStatus($orgId));
        }
        $form->setFormUrl(
            AuthorisedExaminerUrlBuilderWeb::aeEditStatusConfirm($orgId)->queryParam(self::SESSION_KEY, $sessionKey)
        );

        //  save ae status to db and redirect to ae view page
        if ($request->isPost()) {
            try {
                $this->mapper->Organisation->status($form->toDto(), $orgId);

                //  clean session after self
                $this->session->offsetUnset($sessionKey);

                return $this->redirect()->toUrl(AuthorisedExaminerUrlBuilderWeb::of($orgId));
            } catch (RestApplicationException $ve) {
                $this->addErrorMessages($ve->getDisplayMessages());
            }
        }

        //  create a model
        $model = new AeFormViewModel();
        $model
            ->setForm($form)
            ->setCancelUrl(
                AuthorisedExaminerUrlBuilderWeb::aeEditStatus($orgId)->queryParam(self::SESSION_KEY, $sessionKey)
            );

        //  prepare view
        $subTitle = sprintf(
            self::EDIT_SUBTITLE,
            $organisation->getAuthorisedExaminerAuthorisation()->getAuthorisedExaminerRef()
        );
        $breadcrumbs = [$organisation->getName() => $aeViewUrl];
        $contact = $organisation->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY)->getAddress();
        $address = isset($contact)
            ? $organisation->getName() . ' - ' . $contact->getFullAddressString()
            : $organisation->getName();

        return $this->prepareViewModel(
            new ViewModel(['model' => $model]), self::CONFIRM_TITLE, $subTitle, $address, $breadcrumbs, self::STEP_TWO
        );
    }

    /**
     * @param ViewModel $view
     * @param string $title
     * @param string $subtitle
     * @param string $tertiary
     * @param array $breadcrumbs
     * @param string $progress
     *
     * @return ViewModel
     */
    private function prepareViewModel(
        ViewModel $view,
        $title,
        $subtitle,
        $tertiary,
        $breadcrumbs = null,
        $progress = null
    ) {
        //  logical block:: prepare view
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageTitle', $title);
        $this->layout()->setVariable('pageSubTitle', $subtitle);
        $this->layout()->setVariable('pageTertiaryTitle', $tertiary);

        if ($progress !== null) {
            $this->layout()->setVariable('progress', $progress);
        }

        $breadcrumbs = (is_array($breadcrumbs) ? $breadcrumbs : []) + [$title => ''];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        return $view;
    }
}
