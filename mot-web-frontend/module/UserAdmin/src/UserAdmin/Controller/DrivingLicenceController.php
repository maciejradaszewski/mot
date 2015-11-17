<?php

namespace UserAdmin\Controller;

use DvsaCommon\Validator\DrivingLicenceValidator;
use DvsaMotTest\Controller\AbstractDvsaMotTestController;
use UserAdmin\Presenter\DrivingLicenceSummaryPresenter;
use UserAdmin\Service\HelpdeskAccountAdminService;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\Service\PersonRoleManagementService;
use Zend\View\Model\ViewModel;
use UserAdmin\Presenter\UserProfilePresenter;
use UserAdmin\ViewModel\UserProfile\TesterAuthorisationViewModel;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\Role;
use Core\Service\SessionService;
use DvsaCommon\Exception\UnauthorisedException;
use Exception;

/**
 * Class DrivingLicenceController
 * @package UserAdmin\Controller
 */
class DrivingLicenceController extends AbstractDvsaMotTestController
{
    const PAGE_SUBTITLE = 'User profile';
    const MSG_DRIVING_LICENCE_CHANGED_SUCCESSFULLY = 'Driving licence has been changed successfully.';
    const MSG_DRIVING_LICENCE_CHANGED_FAILURE = 'Driving licence could not be changed. Please try again.';
    const SESSION_STORAGE_KEY_PREFIX = 'CHANGE_PERSON_DRIVING_LICENCE';

    /**
     * @var HelpdeskAccountAdminService
     */
    private $accountAdminService;

    /**
     * @var MotAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * @var TesterGroupAuthorisationMapper
     */
    private $testerGroupAuthorisationMapper;

    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * @var PersonRoleManagementService
     */
    private $personRoleManagementService;

    /**
     * @var null|array
     */
    private $validationErrors;

    /**
     * @param HelpdeskAccountAdminService $accountAdminService
     * @param MotAuthorisationServiceInterface $authorisationService
     * @param TesterGroupAuthorisationMapper $testerGroupAuthorisationMapper
     * @param SessionService $sessionService
     * @param PersonRoleManagementService $personRoleManagementService
     */
    public function __construct(
        HelpdeskAccountAdminService $accountAdminService,
        MotAuthorisationServiceInterface $authorisationService,
        TesterGroupAuthorisationMapper $testerGroupAuthorisationMapper,
        SessionService $sessionService,
        PersonRoleManagementService $personRoleManagementService
    ) {
        $this->accountAdminService = $accountAdminService;
        $this->authorisationService = $authorisationService;
        $this->testerGroupAuthorisationMapper = $testerGroupAuthorisationMapper;
        $this->sessionService = $sessionService;
        $this->personRoleManagementService = $personRoleManagementService;
    }

    /**
     * Handle DVSA user adding and editing of a persons driving licence
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $this->authorisationService->assertGranted(PermissionInSystem::ADD_EDIT_DRIVING_LICENCE);

        $personId = $this->params()->fromRoute('personId');
        $profile = $this->accountAdminService->getUserProfile($personId);
        $presenter = $this->createPresenter($personId);

        if ($presenter->hasDvsaRoles()) {
            throw new UnauthorisedException('DVSA cannot modify a DVSA user\'s driving licence');
        }

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageTitle', 'Change driving licence');
        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE);

        if ($data = $this->sessionService->load(self::SESSION_STORAGE_KEY_PREFIX . $personId)) {
            // If we've come from the summary window, we want to load the licence data stored in the session
            $drivingLicenceNumber = $data['drivingLicenceNumber'];
            $drivingLicenceRegion = $data['drivingLicenceRegion'];
        } else {
            // Otherwise load the licence data from the user profile
            $drivingLicenceNumber = $profile->getDrivingLicenceNumber();
            $drivingLicenceRegion = $profile->getDrivingLicenceRegionCode();
        }

        if ($this->getRequest()->isPost()) {
            $params = [
                'drivingLicenceNumber' => $this->getRequest()->getPost('drivingLicenceNumber'),
                'drivingLicenceRegion' => $this->getRequest()->getPost('drivingLicenceRegion'),
            ];

            if ($this->validate($params) && $this->saveToSession($personId, $params)) {
                $profileUrl = UserAdminUrlBuilderWeb::of()->drivingLicenceChangeSummary($personId);
                return $this->redirect()->toUrl($profileUrl);
            } else {
                // Set the driving licence number and region to the one that was submitted to re-display
                $drivingLicenceNumber = $this->getRequest()->getPost('drivingLicenceNumber');
                $drivingLicenceRegion = $this->getRequest()->getPost('drivingLicenceRegion');
            }
        }

        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => [
            $presenter->displayTitleAndFullName() => UserAdminUrlBuilderWeb::of()->userProfile($personId),
            'Change driving licence' => '',
        ]]);

        return new ViewModel([
            'presenter' => $presenter,
            'drivingLicenceNumber' => $drivingLicenceNumber,
            'drivingLicenceRegion' => $drivingLicenceRegion,
            'errors' => $this->validationErrors,
        ]);
    }

    /**
     * Summary screen for changing user's driving licence
     * @return ViewModel
     */
    public function summaryAction()
    {
        $this->authorisationService->assertGranted(PermissionInSystem::ADD_EDIT_DRIVING_LICENCE);

        $personId = $this->params()->fromRoute('personId');
        $request = $this->getRequest();

        // If there is no summary data stored in the session for this user, redirect to their profile
        if (!$data = $this->sessionService->load(self::SESSION_STORAGE_KEY_PREFIX . $personId)) {
            return $this->redirect()->toUrl(UserAdminUrlBuilderWeb::of()->userProfile($personId));
        }

        if ($request->isPost()) {
            if ($this->validate($data)) {
                try {
                    $this->accountAdminService->updateDrivingLicence(
                        $personId,
                        $data['drivingLicenceNumber'],
                        $data['drivingLicenceRegion']
                    );

                    $this->sessionService->save(self::SESSION_STORAGE_KEY_PREFIX . $personId, null);
                    $this->flashMessenger()->addSuccessMessage(self::MSG_DRIVING_LICENCE_CHANGED_SUCCESSFULLY);
                } catch (Exception $e) {
                    $this->flashMessenger()->addErrorMessage(self::MSG_DRIVING_LICENCE_CHANGED_FAILURE);
                }
            }

            // Redirect to the user profile on success or failure and display a relevant message
            $redirectUrl = UserAdminUrlBuilderWeb::of()->userProfile($personId);
            return $this->redirect()->toUrl($redirectUrl);
        }

        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageTitle', 'Review driving licence');
        $this->layout()->setVariable('pageSubTitle', self::PAGE_SUBTITLE);

        $presenter = $this->createPresenter($personId);

        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => [
            $presenter->displayTitleAndFullName() => UserAdminUrlBuilderWeb::of()->userProfile($personId),
            'Change driving licence' => '',
        ]]);

        return new ViewModel([
            'presenter' => (new DrivingLicenceSummaryPresenter)->setPersonId($personId),
            'drivingLicenceNumber' => $data['drivingLicenceNumber'],
            'drivingLicenceRegion' => $data['drivingLicenceRegion'],
        ]);
    }

    /**
     * Validates a driving licence is correct format for the region
     *
     * This method expects that the fields `drivingLicenceNumber` and `drivingLicenceRegion`
     * will be in the POST data of the request. Not providing either of these values will
     * results in validation failing. if the values do not exist in POST, when retrieved they
     * will be null, and also fail validation
     *
     * @param array $params
     * @return bool
     */
    private function validate(array $params)
    {
        $validator = new DrivingLicenceValidator;
        if (!$validator->isValid($params)) {
            $this->validationErrors = $validator->getMessages();
            foreach ($this->validationErrors as $field => $errorMessage) {
                $message = $validator->getFieldLabel($field) . ' - ' . $errorMessage;
                $this->flashMessenger()->addErrorMessage([$message]);
            }
            return false;
        }

        return true;
    }

    /**
     * Save the driving licence number and region to the session
     * @param int $personId
     * @param array $params
     * @return bool
     */
    private function saveToSession($personId, array $params)
    {
        $this->sessionService->save(self::SESSION_STORAGE_KEY_PREFIX . $personId, $params);
        return true;
    }

    /**
     * @param $personId
     * @return UserProfilePresenter
     */
    private function createPresenter($personId)
    {
        $presenter = new UserProfilePresenter(
            $this->accountAdminService->getUserProfile($personId),
            $this->getTesterAuthorisationViewModel($personId),
            null,
            $this->authorisationService->isGranted(PermissionInSystem::VIEW_OTHER_USER_PROFILE_DVSA_USER) &&
            !$this->authorisationService->hasRole(Role::CUSTOMER_SERVICE_CENTRE_OPERATIVE),
            $this->personRoleManagementService
        );

        $presenter->setPersonId($personId);
        return $presenter;
    }

    /**
     * @param $personId
     * @return TesterAuthorisationViewModel
     */
    private function getTesterAuthorisationViewModel($personId)
    {
        return new TesterAuthorisationViewModel(
            $personId,
            $this->testerGroupAuthorisationMapper->getAuthorisation($personId),
            $this->authorisationService
        );
    }
}