<?php


namespace Dashboard\ViewModel;


use Core\Service\MotFrontendAuthorisationServiceInterface;
use Dashboard\Service\TradeRolesAssociationsService;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use Zend\View\Model\ViewModel;

class UserTradeRolesViewModel
{

    /**
     * @var array
     */
    protected $rolesAndAssociations;

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

    /**
     * @param MotFrontendAuthorisationServiceInterface $authorisationService
     */
    public function __construct(MotFrontendAuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Decides if we should display role as a link - checks permission to view site/organisation
     * @param $roletype
     * @param $placeId
     * @return bool
     */
    public function shouldDisplayLink($roletype, $placeId)
    {
        switch ($roletype){
            case TradeRolesAssociationsService::ROLETYPE_ORGANISATIONS: {
                return $this->authorisationService->isGrantedAtOrganisation(
                    PermissionAtOrganisation::AUTHORISED_EXAMINER_READ, $placeId
                );
            }
            case TradeRolesAssociationsService::ROLETYPE_SITES : {
                return $this->authorisationService->isGrantedAtSite(
                    PermissionAtSite::VEHICLE_TESTING_STATION_READ, $placeId
                );
            }
            default:{
                throw new \InvalidArgumentException('Role type not recognized');
            }
        }
    }

    /**
     * Generates URL for
     * @param string $roletype
     * @param int $userId
     * @return string|VehicleTestingStationUrlBuilderWeb|AuthorisedExaminerUrlBuilderWeb
     */
    public function getUrlForRole($roletype, $userId)
    {
        switch ($roletype){
            case TradeRolesAssociationsService::ROLETYPE_ORGANISATIONS: {
                return AuthorisedExaminerUrlBuilderWeb::of($userId);
            }
            case TradeRolesAssociationsService::ROLETYPE_SITES : {
                return VehicleTestingStationUrlBuilderWeb::byId($userId);
            }
            default:{
                throw new \InvalidArgumentException('Role type not recognized');
            }
        }
    }

    /**
     * @param array $rolesAndAssociations
     */
    public function setRolesAndAssociations($rolesAndAssociations)
    {
        $this->rolesAndAssociations = $rolesAndAssociations;
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
     * @return array
     */
    public function getRolesAndAssociations()
    {
        return $this->rolesAndAssociations;
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
}