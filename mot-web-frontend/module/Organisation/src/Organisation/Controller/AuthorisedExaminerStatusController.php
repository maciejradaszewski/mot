<?php

namespace Organisation\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use Organisation\Form\AeStatusForm;
use Organisation\ViewModel\AuthorisedExaminer\AeFormStatusViewModel;
use Organisation\ViewModel\AuthorisedExaminer\AeFormViewModel;
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

            // obtain area office number from the site-number label
            $assignedAO = $organisation->getAuthorisedExaminerAuthorisation()->getAssignedAreaOffice();
            $aoNumber = -1;
            $siteNumber = '';

            if ($assignedAO) {
                $siteNumber = $assignedAO->getSiteNumber();
                $allAreaOffices   = $this->mapper->Organisation->getAllAreaOffices();
                $aoNumber = AuthorisedExaminerController::getAONumberFromName($siteNumber, $allAreaOffices);
            }

            $areaOffices = $this->mapper->Organisation->getAllAreaOffices(true);
            $form->setAssignedAreaOffice($aoNumber);
            $form->setAreaOfficeOptions($areaOffices);

            $form->setStatus($organisation->getAuthorisedExaminerAuthorisation()->getStatus()->getCode());
        }
        $form->setFormUrl(
            AuthorisedExaminerUrlBuilderWeb::aeEditStatus($orgId)->queryParam(self::SESSION_KEY, $sessionKey)
        );

        if ($request->isPost()) {
            $form->fromPost($request->getPost());

            try {
                /** @var \DvsaCommon\Dto\Organisation\OrganisationDto $organisationDto */
                $organisationDto = $form->toDto();
                $this->mapper->Organisation->validateStatusAndAO($organisationDto, $orgId);

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
                // Ensure 'label' reflects the current dropdown entry
                $actualAONumber = $form->getAssignedAreaOffice();
//                $form->setAssignedAreaOfficeLabel($actualAONumber);
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

    /**
     * Asks the API for the list of Area Offices that a user can select
     * to be associated as the controlling AO for this AE entity.
     *
     * @return array|mixed
     */
    protected function getAreaOfficeOptions()
    {
        try {
            return $this->mapper->Organisation->getAllAreaOffices();
        } catch (RestApplicationException $ve) {
            $this->addErrorMessages($ve->getDisplayMessages());
        }
        return [];
    }
}
