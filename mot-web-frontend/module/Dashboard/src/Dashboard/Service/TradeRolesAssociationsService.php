<?php

namespace Dashboard\Service;

use Application\Helper\DataMappingHelper;
use Application\Data\ApiPersonalDetails;
use Application\Service\CatalogService;
use Dashboard\Model\PersonalDetails;

class TradeRolesAssociationsService
{
    private $personalDetailsApi;

    private $catalogService;

    const ROLETYPE_ORGANISATIONS = 'organisations';

    const ROLETYPE_SITES = 'sites';

    /**
     * @param ApiPersonalDetails $personalDetailsApi
     * @param CatalogService $catalogService
     */
    public function __construct(
        ApiPersonalDetails $personalDetailsApi,
        CatalogService $catalogService
    )
    {
        $this->catalogService = $catalogService;
        $this->personalDetailsApi = $personalDetailsApi;
    }

    public function getRolesAndAssociations($personId)
    {
        $personalDetailsData = $this->personalDetailsApi->getPersonalDetailsData($personId);

        $personalDetails = new PersonalDetails($personalDetailsData);

        return $this->prepareRolesAndAssociations($personalDetails);
    }

    private function prepareRolesAndAssociations(PersonalDetails $personalDetails)
    {
        $rolesAndAssociations = [];
        $siteAndOrganisationRoles = $personalDetails->getSiteAndOrganisationRoles();

        $personSiteAndOrganisationRoles = $this->catalogService->getBusinessRoles();

        foreach ($siteAndOrganisationRoles as $siteAndOrganisation) {
            $id = $siteAndOrganisation["id"];
            $siteAndOrganisationData = $siteAndOrganisation["data"];
            foreach ($siteAndOrganisationData['roles'] as $role) {
                $niceName = (new DataMappingHelper($personSiteAndOrganisationRoles, 'code', $role))
                    ->setReturnKeys(['name'])
                    ->getValue();

                $rolesAndAssociations[] = $this->createRoleData(
                    $role,
                    $niceName['name'],
                    $id,
                    $siteAndOrganisationData["name"],
                    $siteAndOrganisationData["address"]
                );
            }
        }

        return $rolesAndAssociations;
    }

    private function createRoleData($role, $nicename, $id = "", $name = "", $address = "")
    {
        return [
            'id'       => $id,
            'role'     => $role,
            'nicename' => $nicename,
            'name'     => $name,
            'address'  => $address
        ];
    }
}
