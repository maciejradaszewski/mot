<?php

namespace OrganisationApi\Model;

use DvsaCommonApi\Service\Validator\ErrorSchema;
use DvsaEntities\Entity\Person;

/**
 * Interface RoleRestrictionInterface.
 */
interface RoleRestrictionInterface
{
    /**
     * Checks if all requirements are met to assign a to the user in the given organisation.
     * Return unmet conditions.
     *
     * @param Person                $person
     * @param OrganisationPersonnel $personnel
     *
     * @return ErrorSchema
     */
    public function verify(Person $person, OrganisationPersonnel $personnel);

    /**
     * @return string The role this restriction applies to
     */
    public function getRole();
}
