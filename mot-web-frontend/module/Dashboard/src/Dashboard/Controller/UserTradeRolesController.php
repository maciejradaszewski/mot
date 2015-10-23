<?php

namespace Dashboard\Controller;

use Application\Data\ApiPersonalDetails;
use Application\Helper\DataMappingHelper;
use Application\Service\CatalogService;
use Core\Controller\AbstractAuthActionController;
use Core\Service\MotFrontendIdentityProviderInterface;
use Dashboard\Model\PersonalDetails;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use Zend\View\Model\ViewModel;

class UserTradeRolesController extends AbstractAuthActionController
{
    private $personalDetailsApi;

    private $catalogService;

    private $identityProvider;

    public function __construct(
        ApiPersonalDetails $personalDetailsApi,
        CatalogService $catalogService,
        MotFrontendIdentityProviderInterface $identityProvider
    )
    {
        $this->catalogService = $catalogService;
        $this->personalDetailsApi = $personalDetailsApi;
        $this->identityProvider = $identityProvider;
    }

    public function indexAction()
    {
        $personId = (int)$this->params()->fromRoute('id');

        $personalDetailsData = $this->personalDetailsApi->getPersonalDetailsData($personId);

        $personalDetails = new PersonalDetails($personalDetailsData);

        $rolesAndAssociations = $this->getRolesAndAssociations($personalDetails);
        $profileUrl = PersonUrlBuilderWeb::profile((int)$this->params()->fromRoute('id'));

        $this->setPageTitle('Roles and Associations');

        if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
            $this->setPageSubTitle('Your profile');
        } else {
            $this->setPageSubTitle('User profile');
        }

        if (!$rolesAndAssociations) {
            if ($personId == $this->identityProvider->getIdentity()->getUserId()) {
                $this->setPageLede("You don't have any roles and associations");
            } else {
                $this->setPageLede("User doesn't have any active role associations");
            }
        }

        $this->layout('layout/layout-govuk.phtml');

        return new ViewModel([
            'rolesAndAssociations' => $rolesAndAssociations,
            'backToProfileUrl' => $profileUrl,
        ]);
    }

    private function getRolesAndAssociations(PersonalDetails $personalDetails)
    {
        $rolesAndAssociations = [];
        $siteAndOrganisationRoles = $personalDetails->getSiteAndOrganisationRoles();

        $personSiteAndOrganisationRoles = $this->catalogService->getBusinessRoles();

        foreach ($siteAndOrganisationRoles as $id => $siteAndOrganisationRole) {
            foreach ($siteAndOrganisationRole['roles'] as $role) {
                $niceName = (new DataMappingHelper($personSiteAndOrganisationRoles, 'code', $role))
                    ->setReturnKeys(['name'])
                    ->getValue();

                $rolesAndAssociations[] = $this->createRoleData(
                    $role,
                    $niceName['name'],
                    $id,
                    $siteAndOrganisationRole["name"],
                    $siteAndOrganisationRole["address"]
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
