<?php

namespace UserAdmin\Presenter;

use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\EventUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use Core\Presenter\AddressPresenterInterface;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\Constants\Role;
use DvsaCommon\Utility\AddressUtils;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\Mvc\Controller\Plugin\Url as UrlPlugin;

/**
 * Decorator for PersonHelpDeskProfileDto
 */
class UserProfilePresenter implements AddressPresenterInterface
{
    /** DVSA user profile template */
    const DVSA_PROFILE_TEMPLATE = 'user-admin/user-profile/dvsa-profile.phtml';
    /** Unrestricted user profile template path */
    const UNRESTRICTED_PROFILE_TEMPLATE = 'user-admin/user-profile/unrestricted-profile.phtml';

    /* @var int */
    private $id;
    /* @var PersonHelpDeskProfileDto $person */
    private $person;
    /** @var array */
    private $testerQualificationStatusService;
    /* @var bool */
    private $isDvsaUser;

    /**
     * @param PersonHelpDeskProfileDto $person
     * @param TesterQualificationStatusService $testerQualificationStatusService
     * @param bool $isDvsaUser
     */
    public function __construct(
        PersonHelpDeskProfileDto $person,
        $testerQualificationStatus,
        $isDvsaUser = false)
    {
        $this->person = $person;
        $this->testerQualificationStatus = $testerQualificationStatus;
        $this->isDvsaUser = $isDvsaUser;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
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
        return EventUrlBuilderWeb::of()->eventList($this->getId(), 'person');
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
     * @return array
     */
    public function getQualificationStatus()
    {
        return $this->testerQualificationStatus;
    }

    /**
     * @return bool
     */
    public function hasQualificationStatus()
    {
        return !empty($this->testerQualificationStatus);
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
}
