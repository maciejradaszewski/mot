<?php

namespace PersonApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\PermissionToAssignRoleMap;
use DvsaEntities\Entity\PersonSystemRole;
use DvsaEntities\Entity\PersonSystemRoleMap;
use DvsaEntities\Entity\Role;
use DvsaEntities\Repository\PermissionToAssignRoleMapRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\PersonSystemRoleMapRepository;
use DvsaEntities\Repository\PersonSystemRoleRepository;
use DvsaEntities\Repository\RbacRepository;
use DvsaEntities\Repository\RoleRepository;
use DvsaMotApi\Helper\RoleEventHelper;
use DvsaMotApi\Helper\RoleNotificationHelper;

class PersonRoleService
{
    const ERR_MSG_TRADE_ROLE_OWNER = 'Its not possible to assign an "internal" role to a "trade" role owner';

    const ROLES_ALL = 'all';
    const ROLES_ASSIGNED = 'assigned';
    const ROLES_MANAGEABLE = 'manageable';

    /** @var PersonRepository */
    private $personRepository;

    /** @var PersonSystemRoleRepository */
    private $personSystemRoleRepository;

    /** @var PersonSystemRoleMapRepository */
    private $personSystemRoleMapRepository;

    /** @var RoleRepository */
    private $roleRepository;

    /** @var array */
    private $personCurrentInternalRoles;

    /** @var EntityRepository */
    private $businessRoleStatusRepository;

    /** @var PermissionToAssignRoleMap */
    private $permissionToAssignRoleMap;

    /** @var AuthorisationService */
    private $authService;

    /** @var RoleEventHelper */
    private $roleEventHelper;

    /** @var RoleNotificationHelper */
    private $roleNotificationHelper;

    /** @var RbacRepository */
    private $rbacRepository;

    /**
     * @param RbacRepository $rbacRepository
     */
    public function __construct(
        RbacRepository $rbacRepository,
        EntityRepository $businessRoleStatusRepository,
        PermissionToAssignRoleMapRepository $permissionToAssignRoleMapRepository,
        PersonRepository $personRepository,
        PersonSystemRoleRepository $personSystemRoleRepository,
        PersonSystemRoleMapRepository $personSystemRoleMapRepository,
        RoleRepository $roleRepository,
        AuthorisationServiceInterface $authService,
        RoleEventHelper $roleEventHelper,
        RoleNotificationHelper $roleNotificationHelper
    )
    {
        $this->rbacRepository = $rbacRepository;

        $this->businessRoleStatusRepository = $businessRoleStatusRepository;
        $this->permissionToAssignRoleMap = $permissionToAssignRoleMapRepository;
        $this->personRepository = $personRepository;
        $this->personSystemRoleRepository = $personSystemRoleRepository;
        $this->personSystemRoleMapRepository = $personSystemRoleMapRepository;
        $this->roleRepository = $roleRepository;

        $this->authService = $authService;
        $this->roleEventHelper = $roleEventHelper;
        $this->roleNotificationHelper = $roleNotificationHelper;
    }


    /**
     * Add the role to the user
     * @param int $personId
     * @param array $data ['personSystemRoleCode' => 'SOME-ROLE-CODE']
     * @return PersonSystemRoleMap
     * @throws \Exception If user has a trade role
     */
    public function create($personId, $data)
    {
        // Check the permission to use this feature has been granted
        $this->authService->assertGranted(PermissionInSystem::MANAGE_DVSA_ROLES);

        if ($this->personHasTradeRole($personId)) {
            throw new \Exception(self::ERR_MSG_TRADE_ROLE_OWNER);
        }

        $person = $this->getPersonEntity($personId);
        $personSystemRole = $this->getPersonSystemRoleEntityFromName($data['personSystemRoleCode']);
        $role = $personSystemRole->getRole();

        $permission = $this->getPermissionCodeFromRole($role);

        $this->authService->assertGranted($permission);

        $obj = $this->assignRole($person, $personSystemRole);

        $this->roleEventHelper->createAssignRoleEvent($person, $personSystemRole);
        $this->roleNotificationHelper->sendAssignRoleNotification($person, $personSystemRole);

        return $obj;
    }

    public function delete()
    {
//        $this->createEvent();
//        $this->sendNotification();
    }

    /**
     * Attaches the role to the person
     * @param Person $person
     * @param PersonSystemRole $personSystemRole
     * @return PersonSystemRoleMap
     * @throws \Exception if the mapping for the person already exists
     */
    public function assignRole(Person $person, PersonSystemRole $personSystemRole)
    {
        $personSystemRoleMap = $this->personSystemRoleMapRepository->findByPersonAndSystemRole(
            $person->getId(),
            $personSystemRole->getId()
        );

        // If the mapping already exists in the DB we want to throw an exception
        if($personSystemRoleMap instanceof PersonSystemRoleMap) {
            throw new \Exception('PersonSystemRoleMap already exists');
        }

        // No mapping exists, make one
        $personSystemRoleMap = (new PersonSystemRoleMap())
            ->setPerson($person)
            ->setPersonSystemRole($personSystemRole);

        // Set the status to active
        $roleStatus = $this->businessRoleStatusRepository->findOneBy(
            ['code' => BusinessRoleStatusCode::ACTIVE]
        );
        $personSystemRoleMap->setBusinessRoleStatus($roleStatus);

        $this->personSystemRoleMapRepository->save($personSystemRoleMap);
        return $personSystemRoleMap;
    }

    /**
     * Returns both assigned and manageable roles for the given person
     * @param int $personId
     * @return array
     */
    public function getRoles($personId)
    {
        $this->authService->assertGranted(PermissionInSystem::MANAGE_DVSA_ROLES);
        return $this->getPersonAssignedAndAvailableInternalRoleCodes($personId);
    }

    /**
     * Retrieve the role entity from the DB
     * @param string $roleName
     * @return PersonSystemRole
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getPersonSystemRoleEntityFromName($roleName)
    {
        return $this->personSystemRoleRepository->getByName($roleName);
    }

    /**
     * Retrieves the person entity from the DB
     * @param int $personId
     * @return Person
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    private function getPersonEntity($personId)
    {
        // PersonRepository
        $person = $this->personRepository->find($personId);
        if(!$person instanceof Person) {
            throw new NotFoundException('Unable to find person with id '.$personId);
        }
        return $person;
    }

    /**
     * @param int $personId
     * @return array
     */
    private function getPersonAssignedAndAvailableInternalRoleCodes($personId)
    {
        return [
            self::ROLES_ASSIGNED => $this->getPersonAssignedInternalRoleCodes($personId),
            self::ROLES_MANAGEABLE => $this->getPersonManageableInternalRoleCodes($personId)
        ];
    }

    /**
     * Return an array of all the internal role codes, associated to the given person
     *
     * @param int $personId
     * @return array
     */
    public function getPersonAssignedInternalRoleCodes($personId)
    {
        if (is_null($this->personCurrentInternalRoles)) {
            $this->personCurrentInternalRoles = array_column(
                $this->personSystemRoleMapRepository->getPersonActiveInternalRoleCodes($personId),
                'code'
            );
        }

        return $this->personCurrentInternalRoles;
    }

    /**
     * Return an array of all the internal role codes, NOT associated to the given person,
     * And the logged in person has the permission to manage (add/remove) them
     *
     * @param int $personToAssignRoleTo
     * @return array
     */
    public function getPersonManageableInternalRoleCodes($personToAssignRoleTo)
    {
        $manageableInternalRoles = [];

        /** @var Role $role */
        foreach ($this->getPersonAvailableInternalRoles($personToAssignRoleTo) as $role) {
            if ($this->authService->isGranted($this->getPermissionCodeFromRole($role))) {
                $manageableInternalRoles[] = $role->getCode();
            }
        }

        return $manageableInternalRoles;
    }

    /**
     * Uses the mapping repository to return the relevant permission code
     * @param Role $role
     * @return string
     * @throws \DvsaEntities\Repository\NotFoundException
     */
    private function getPermissionCodeFromRole(Role $role)
    {
        return $this->permissionToAssignRoleMap->getPermissionCodeByRoleCode($role->getCode());
    }

    /**
     * Return an array of all the internal roles, NOT associated to the given person
     *
     * @param int $personId
     * @return Role[]
     */
    private function getPersonAvailableInternalRoles($personId)
    {
        $currentInternalRoles = $this->getPersonAssignedInternalRoleCodes($personId);

        $availableInternalRoles = array_filter(
            $this->roleRepository->getAllInternalRoles($personId),
            function (Role $role) use ($currentInternalRoles) {
                return !in_array($role->getCode(), $currentInternalRoles);
            }
        );

        return $availableInternalRoles;
    }

    /**
     * To identify if the given person has any trade roles
     * @param int $personId
     * @return bool
     */
    public function personHasTradeRole($personId)
    {
        $allTradeRoles = $this->getAllTradeRoleCodes();
        $personRoles = $this->getPersonRoles($personId);
        $intersect = array_intersect($allTradeRoles,$personRoles);

        return !empty($intersect);
    }

    /**
     * Returns an array of all trade role codes
     * @return array
     */
    private function getAllTradeRoleCodes()
    {
        return array_map(
            function (Role $role) {
                return $role->getCode();
            },
            $this->roleRepository->getAllTradeRoles()
        );
    }

    /**
     * Returns an array of all role codes, assigned to the given person in all three different scopes.
     * Including system, site and organisation level
     *
     * @param $personId
     * @return array
     */
    private function getPersonRoles($personId)
    {
        return $this->rbacRepository->authorizationDetails($personId)->getAllRoles();
    }
}
