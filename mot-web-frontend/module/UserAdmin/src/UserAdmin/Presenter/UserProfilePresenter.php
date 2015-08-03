<?php

namespace UserAdmin\Presenter;

use DvsaCommon\Model\DvsaRole;
use DvsaClient\Entity\TesterAuthorisation;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\EventUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use Core\Presenter\AddressPresenterInterface;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\Constants\Role;
use DvsaCommon\Utility\AddressUtils;
use UserAdmin\Service\PersonRoleManagementService;
use Zend\Di\Exception\RuntimeException;
use Zend\Mvc\Controller\Plugin\Url as UrlPlugin;
use UserAdmin\ViewModel\UserProfile\TesterAuthorisationViewModel;

/**
 * Decorator for PersonHelpDeskProfileDto
 */
class UserProfilePresenter implements AddressPresenterInterface
{
    /** DVSA user profile template */
    const DVSA_PROFILE_TEMPLATE = 'user-admin/user-profile/dvsa-profile.phtml';

    /** Unrestricted user profile template path */
    const UNRESTRICTED_PROFILE_TEMPLATE = 'user-admin/user-profile/unrestricted-profile.phtml';
    /** Change email template */
    const CHANGE_EMAIL_TEMPLATE = 'user-admin/email-address/form.phtml';

    /* @var int */
    private $id;
    /* @var PersonHelpDeskProfileDto $person */
    private $person;
    /* @var bool */
    private $isDvsaUser;

    /** @var TesterAuthorisationViewModel */
    private $testerAuthorisation;

    /** @var PersonRoleManagementService s*/
    private $personRoleManagementService;

    /**
     * @param PersonHelpDeskProfileDto $person
     * @param TesterAuthorisationViewModel $testerAuthorisation
     * @param bool|false $isDvsaUser
     * @param PersonRoleManagementService|null $personRoleManagementService
     */
    public function __construct(
        PersonHelpDeskProfileDto $person,
        TesterAuthorisationViewModel $testerAuthorisation,
        $isDvsaUser = false,
        PersonRoleManagementService $personRoleManagementService = null
    )
    {
        $this->person = $person;
        $this->testerAuthorisation = $testerAuthorisation;
        $this->isDvsaUser = $isDvsaUser;
        $this->personRoleManagementService = $personRoleManagementService;
    }

    public function setPersonId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getPersonId()
    {
        return $this->id;
    }

    /**
     * @return string format: userName
     */
    public function displayUserName()
    {
        return $this->person->getUserName();
    }

    /**
     * @return string format: j F Y
     */
    public function displayDateOfBirth()
    {
        return DateTimeDisplayFormat::textDate($this->person->getDateOfBirth());
    }

    /**
     * @return string format: title firstName lastName
     */
    public function displayTitleAndFullName()
    {
        return join(
            ' ', array_filter(
                [
                    $this->person->getTitle(),
                    $this->person->getFirstName(),
                    $this->person->getMiddleName(),
                    $this->person->getLastName(),
                ]
            )
        );
    }

    /**
     * @return string format: firstName lastName
     */
    public function displayFullName()
    {
        return implode(' ', [$this->person->getFirstName(), $this->person->getLastName()]);
    }

    public function displayDrivingLicenceNumber()
    {
        return $this->person->getDrivingLicenceNumber();
    }

    /**
     * @return string
     */
    public function displayAddressLine()
    {
        return AddressUtils::stringify($this->person->getAddress());
    }

    /**
     * @return string
     */
    public function displayAddressLine1()
    {
        return $this->person->getAddress()->getAddressLine1();
    }

    /**
     * @return string
     */
    public function displayAddressLine2()
    {
        return $this->person->getAddress()->getAddressLine2();
    }

    /**
     * @return string
     */
    public function displayAddressLine3()
    {
        return $this->person->getAddress()->getAddressLine3();
    }

    /**
     * @return string
     */
    public function displayAddressLine4()
    {
        return $this->person->getAddress()->getAddressLine4();
    }

    /**
     * @return string
     */
    public function displayPostcode()
    {
        return $this->person->getAddress()->getPostcode();
    }

    /**
     * @return string
     */
    public function displayTown()
    {
        return $this->person->getAddress()->getTown();
    }

    /**
     * @return string
     */
    public function displayFullAddress()
    {
        return $this->person->getAddress()->getFullAddressString();
    }

    /**
     * @return string
     */
    public function displayEmail()
    {
        return $this->person->getEmail();
    }

    /**
     * @return string
     */
    public function displayTelephone()
    {
        return $this->person->getTelephone();
    }

    /**
     * @return string
     */
    public function displayEventsHistoryLink()
    {
        return EventUrlBuilderWeb::of()->eventList($this->getPersonId(), 'person');
    }

    /**
     * Returns the persons assigned system roles. USER and CRON are filtered
     * from roles as we don't want to display these in the user profile
     * @return array
     */
    public function getSystemRoles()
    {
        $roles = $this->person->getRoles();
        $rolesFiltered = [];
        foreach ($roles['system']['roles'] as $role) {
            if ($role != Role::CRON && $role != Role::USER && $role != Role::TESTER_ACTIVE) {
                $rolesFiltered[] = $role;
            }
        }
        return $rolesFiltered;
    }

    /**
     * Returns an array of all site and organisation roles,
     * grouped by site/organisation ID
     * @return array
     */
    public function getSiteAndOrganisationRoles()
    {
        $roles = $this->person->getRoles();
        $processedRoles = [];

        foreach ($roles['organisations'] as $id => $organisationData) {
            $organisationData['route'] = AuthorisedExaminerUrlBuilderWeb::of($id);
            $processedRoles[$id] = $organisationData;
        }

        foreach ($roles['sites'] as $id => $siteData) {
            $siteData['route'] = VehicleTestingStationUrlBuilderWeb::byId($id);
            $processedRoles[$id] = $siteData;
        }

        return $processedRoles;
    }

    /**
     * Get the profile template depending on the authentication status
     *
     * @return string
     */
    public function getTemplate()
    {
        if ($this->isDvsaUser) {
            return self::DVSA_PROFILE_TEMPLATE;
        }
        return self::UNRESTRICTED_PROFILE_TEMPLATE;
    }

    /**
     * Return an array of all the internal role codes assigned to the person
     *
     * @return array
     */
    public function getPersonAssignedInternalRoleCodes()
    {
        if (is_null($this->personRoleManagementService)) {
            throw new RuntimeException(
                sprintf(
                    'In order to use %s method you have to inject %s service during initiation of %s',
                    __METHOD__, PersonRoleManagementService::class, get_class($this)
                )
            );
        }

        return array_column(
            $this->personRoleManagementService->getPersonAssignedInternalRoles($this->getPersonId()),
            'name'
        );
    }

    /**
     * To find out if we can display the "role" section
     * @return bool
     */
    public function canDisplayRoleSection()
    {
        $userHasPermission = $this->personRoleManagementService->userHasPermissionToManagePersonDvsaRoles();
        $haveNoAssociatedRole = empty($this->getSiteAndOrganisationRoles());

        return $userHasPermission && $haveNoAssociatedRole;
    }

    public function getTesterAuthorisation()
    {
        return $this->testerAuthorisation;
    }

}
