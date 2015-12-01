<?php


namespace Dashboard\ViewModel;

use Core\Catalog\BusinessRole\BusinessRole;
use Core\Catalog\BusinessRole\BusinessRoleCatalog;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use Dashboard\Controller\UserTradeRolesController;
use DvsaCommon\ApiClient\Person\PersonTradeRoles\Dto\PersonTradeRoleDto;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Model\OrganisationBusinessRoleCode as OrganisationRoleCode;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use Zend\View\Helper\Url;

class UserTradeRolesViewModel
{
    /**
     * @var int
     */
    protected $personId;

    /**
     * @var bool
     */
    protected $personIsViewingOwnProfile;

    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    protected $authorisationService;

    private $tradeRoles;

    private $businessRoleCatalog;

    /** @var Url */
    private $urlHelper;

    const ROUTE_SITE = 'vehicle-testing-station';
    const ROUTE_ORGANISATION = 'authorised-examiner';

    /**
     * @param MotFrontendAuthorisationServiceInterface $authorisationService
     * @param PersonTradeRoleDto[] $tradeRoles
     * @param BusinessRoleCatalog $businessRoleCatalog
     * @param Url $urlHelper
     */
    public function __construct(MotFrontendAuthorisationServiceInterface $authorisationService,
                                array $tradeRoles,
                                BusinessRoleCatalog $businessRoleCatalog,
                                Url $urlHelper
    )
    {
        $this->authorisationService = $authorisationService;
        $this->tradeRoles = $tradeRoles;
        $this->businessRoleCatalog = $businessRoleCatalog;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Decides if we should display role as a link - checks permission to view site/organisation
     * @param PersonTradeRoleDto $personTradeRole
     * @return bool
     */
    public function shouldDisplayLink(PersonTradeRoleDto $personTradeRole)
    {
        $roleType = $this->businessRoleCatalog->getByCode($personTradeRole->getRoleCode())->getType();
        $workplaceId = $personTradeRole->getWorkplaceId();

        switch ($roleType) {
            case BusinessRole::ORGANISATION_TYPE: {
                return $this->authorisationService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AUTHORISED_EXAMINER_READ, $workplaceId
                );
            }
            case BusinessRole::SITE_TYPE: {
                return $this->authorisationService->isGrantedAtSite(
                    PermissionAtSite::VEHICLE_TESTING_STATION_READ, $workplaceId
                );
            }
            default: {
                throw new \InvalidArgumentException('Role type not recognized');
            }
        }
    }

    /**
     * Generates URL for
     * @param PersonTradeRoleDto $personTradeRole
     * @return string|VehicleTestingStationUrlBuilderWeb|AuthorisedExaminerUrlBuilderWeb
     */
    public function getUrlForRole(PersonTradeRoleDto $personTradeRole)
    {
        $roleType = $this->businessRoleCatalog->getByCode($personTradeRole->getRoleCode())->getType();

        switch ($roleType) {
            case BusinessRole::ORGANISATION_TYPE: {
                return $this->urlHelper->__invoke(self::ROUTE_ORGANISATION, ['id' => $personTradeRole->getWorkplaceId()]);
            }
            case BusinessRole::SITE_TYPE: {
                return $this->urlHelper->__invoke(self::ROUTE_SITE, ['id' => $personTradeRole->getWorkplaceId()]);
            }
            default: {
                throw new \InvalidArgumentException('Role type not recognized');
            }
        }
    }

    /**
     * Generates URL for removing trade role
     * @param PersonTradeRoleDto $personTradeRole
     * @return string|VehicleTestingStationUrlBuilderWeb|AuthorisedExaminerUrlBuilderWeb
     */
    public function getUrlForRemoveRole(PersonTradeRoleDto $personTradeRole)
    {
        $roleType = $this->businessRoleCatalog->getByCode($personTradeRole->getRoleCode())->getType();

        switch ($roleType) {
            case BusinessRole::ORGANISATION_TYPE:
                $route = UserTradeRolesController::ROUTE_REMOVE_AE_ROLE;
                break;
            case BusinessRole::SITE_TYPE:
                $route = UserTradeRolesController::ROUTE_REMOVE_VTS_ROLE;
                break;

            default: {
                throw new \InvalidArgumentException('Role type not recognized');
            }
        }

        return $this->urlHelper->__invoke($route, [
            'id' => $this->personId,
            'positionId' => $personTradeRole->getPositionId(),
            'entityId' => $personTradeRole->getWorkplaceId(),
        ]);
    }

    public function canBeRemoved(PersonTradeRoleDto $tradeRole)
    {
        return $this->personIsViewingOwnProfile && $tradeRole->getRoleCode() != OrganisationRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER;
    }

    public function getTradeRoleNameByCode($roleCode)
    {
        return $this->businessRoleCatalog->getByCode($roleCode)->getName();
    }

    /**
     * @param string $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * @param bool $personIsViewingOwnProfile
     */
    public function setPersonIsViewingOwnProfile($personIsViewingOwnProfile)
    {
        $this->personIsViewingOwnProfile = $personIsViewingOwnProfile;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @return boolean
     */
    public function getPersonIsViewingOwnProfile()
    {
        return $this->personIsViewingOwnProfile;
    }

    public function getTradeRoles()
    {
        return $this->tradeRoles;
    }

    public function isRoleTypeSite(PersonTradeRoleDto $position){
        return $this->businessRoleCatalog->getByCode($position->getRoleCode())->getType() == BusinessRole::SITE_TYPE;
    }
}