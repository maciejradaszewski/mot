<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommon\Enum\BusinessRoleName;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonSystemRoleMap;
use DvsaEntities\Repository\PersonSystemRoleRepository;

/**
 * Class BusinessRoleAssigner.
 */
class BusinessRoleAssigner extends AbstractPersistableService
{
    const EXP_NON_BUSINESS_ROLE = '%s is not a business/system role, see BusinessRoleName';
    /**
     * @var Person
     */
    private $person;

    /**
     * @var PersonSystemRoleRepository
     */
    private $personSystemRoleRepository;

    /**
     * BusinessRoleStatus entity doesn't have costume repository.
     *
     * @var EntityRepository
     */
    private $businessRoleStatusRepository;

    /**
     * @param EntityManager              $entityManager
     * @param PersonSystemRoleRepository $personSystemRoleRepository
     * @param EntityRepository           $businessRoleStatusRepository
     */
    public function __construct(
        EntityManager $entityManager,
        PersonSystemRoleRepository $personSystemRoleRepository,
        EntityRepository $businessRoleStatusRepository
    ) {
        parent::__construct($entityManager);

        $this->personSystemRoleRepository = $personSystemRoleRepository;
        $this->businessRoleStatusRepository = $businessRoleStatusRepository;
    }

    /**
     * Assign the given business/system roles to the given person and return the map.
     *
     * @param Person $person
     * @param string $role   Person system role's name
     *
     * @return PersonSystemRoleMap
     */
    public function assignRoleToPerson(Person $person, $role)
    {
        if (!in_array($role, BusinessRoleName::getAll())) {
            throw new \OutOfRangeException(
                sprintf(self::EXP_NON_BUSINESS_ROLE, $role)
            );
        }

        $this->person = $person;

        $roleStatus = $this->businessRoleStatusRepository->findOneBy(['code' => BusinessRoleStatusCode::ACTIVE]);
        $personSystemRole = $this->personSystemRoleRepository->findOneBy(['name' => $role]);

        $personSystemRoleMap = new PersonSystemRoleMap();
        $personSystemRoleMap->setPerson($person)
            ->setBusinessRoleStatus($roleStatus)
            ->setPersonSystemRole($personSystemRole);

        $this->save($personSystemRoleMap);

        $person->setPersonSystemRoleMaps([$personSystemRoleMap]);

        return $personSystemRoleMap;
    }
}
