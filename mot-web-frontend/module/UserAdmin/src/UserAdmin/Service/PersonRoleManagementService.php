<?php

namespace UserAdmin\Service;

use Application\Helper\DataMappingHelper;
use Application\Service\CatalogService;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaClient\Mapper\UserAdminMapper;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;

class PersonRoleManagementService
{
    const ROLES_ASSIGNED = 'assigned';

    const ROLES_MANAGEABLE = 'manageable';

    const TRADE_ROLE_TYPE = 'trade';

    const INTERNAL_ROLE_TYPE = 'internal';

    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;

    /** @var HttpRestJsonClient */
    private $client;

    /** @var UserAdminMapper */
    private $userAdminMapper;

    /** @var CatalogService */
    private $catalogService;

    /** @var  array containing person's both internal and trade roles*/
    private $personInternalRoles;

    /**
     * @param UserAdminMapper $userAdminMapper
     * @param CatalogService $catalogService
     */
    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        HttpRestJsonClient $client,
        CatalogService $catalogService
    ) {
        $this->authorisationService = $authorisationService;
        $this->client = $client;
        $this->userAdminMapper = new UserAdminMapper($client);
        $this->catalogService = $catalogService;
    }

    public function userHasPermissionToManagePersonDvsaRoles()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::MANAGE_DVSA_ROLES);
    }

    /**
     * @param int $personId
     * @param int $personSystemRoleId
     * @return boolean
     */
    public function addRole($personId, $personSystemRoleId)
    {
        // Use CatalogService to translate between personSystemRoleId and code
        $array = $this->catalogService->getPersonSystemRoles();
        $foundItem = (new DataMappingHelper($array, 'id', (int) $personSystemRoleId))
            ->setReturnKeys(['code'])
            ->getValue();

        $url = PersonUrlBuilder::manageInternalRoles($personId)->toString();
        // API throws an exception if the post fails, catch that here and return
        try {
            $this->client->post($url, [
                'personSystemRoleCode' => $foundItem['code']
            ]);
            return true;
        } catch (GeneralRestException $e) {
            return false;
        }
    }

    /**
     * @param int $personId
     * @return \DvsaCommon\Dto\Person\PersonHelpDeskProfileDto
     */
    public function getUserProfile($personId)
    {
        return $this->userAdminMapper->getUserProfile($personId);
    }

    /**
     * @param int $personId
     * @return array
     */
    public function getPersonManageableInternalRoles($personId)
    {
        $personSystemRoles = $this->catalogService->getPersonSystemRoles();

        $manageableRoles = [];

        foreach ($this->retrievePersonManageableInternalRoles($personId) as $roleCode) {
            $manageableRoles[$roleCode] = (new DataMappingHelper($personSystemRoles, 'code', $roleCode))
                ->setReturnKeys(['id', 'name'])
                ->getValue();
        }

        $manageableRolesAndUrl = array_map(
            function ($element) use ($personId) {
                $element['url'] = [
                    'route' => 'user_admin/user-profile/manage-user-internal-role/assign-internal-role',
                    'params' => [
                        'personId' => $personId,
                        'personSystemRoleId' => $element['id'],
                    ]
                ];
                return $element;
            },
            $manageableRoles
        );

        ksort($manageableRolesAndUrl);

        return $manageableRolesAndUrl;
    }

    /**
     * @param int $personId
     * @return array
     */
    public function getPersonAssignedInternalRoles($personId)
    {
        if (!$this->userHasPermissionToManagePersonDvsaRoles()) {
            return [];
        }

        $personSystemRoles = $this->catalogService->getPersonSystemRoles();

        $manageableRoles = [];

        foreach ($this->retrievePersonAssignedInternalRoles($personId) as $roleCode) {
            $manageableRoles[$roleCode] = (new DataMappingHelper($personSystemRoles, 'code', $roleCode))
                ->setReturnKeys(['id', 'name'])
                ->getValue();
        }

        $manageableRolesAndUrl = array_map(
            function ($element) use ($personId) {
                $element['url'] = [
                    'route' => 'user_admin/user-profile/manage-user-internal-role/remove-internal-role',
                    'params' => [
                        'personId' => $personId,
                        'personSystemRoleId' => $element['id'],
                    ]
                ];
                return $element;
            },
            $manageableRoles
        );

        ksort($manageableRolesAndUrl);

        return $manageableRolesAndUrl;
    }

    private function retrievePersonAssignedAndInternalRoles($personId)
    {
        if (is_null($this->personInternalRoles)) {
            $url = PersonUrlBuilder::manageInternalRoles($personId)->toString();
            $response = $this->client->get($url);

            $this->personInternalRoles = $response['data'];
        }

        return $this->personInternalRoles;
    }

    private function retrievePersonAssignedInternalRoles($personId)
    {
        return $this->retrievePersonAssignedAndInternalRoles($personId)[self::ROLES_ASSIGNED];
    }

    private function retrievePersonManageableInternalRoles($personId)
    {
        return $this->retrievePersonAssignedAndInternalRoles($personId)[self::ROLES_MANAGEABLE];
    }
}
