<?php

namespace SiteApi\Model\RoleRestriction;

use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Validator\ErrorSchema;
use DvsaEntities\Entity\Person;
use SiteApi\Model\SitePersonnel;

/**
 * Class SiteManagerRestriction
 *
 * @package SiteApi\Model\RoleRestriction
 */
class SiteManagerRestriction extends AbstractSiteRoleRestriction
{
    const SITE_ALREADY_HAS_SITE_MANAGER = 'There is already a Site Manager assigned to this Vehicle Testing Station';

    /**
     * Checks if all requirements are met to assign a role to the user in the given organisation.
     * Return unmet conditions.
     *
     * @param Person        $person
     * @param SitePersonnel $personnel
     *
     * @return ErrorSchema
     */
    public function verify(Person $person, SitePersonnel $personnel)
    {
        $errors = parent::verify($person, $personnel);

        if ($this->hasSiteManager($personnel)) {
            $errors->add(self::SITE_ALREADY_HAS_SITE_MANAGER);
        }

        return $errors;
    }

    private function hasSiteManager(SitePersonnel $personnel)
    {
        return ArrayUtils::anyMatch(
            $personnel->getPositions(), function (SiteBusinessRoleMap $position) {
                return $position->getSiteBusinessRole()->getCode() == $this->getRole();
            }
        );
    }

    /**
     * @return String The role this restriction applies to
     */
    public function getRole()
    {
        return SiteBusinessRoleCode::SITE_MANAGER;
    }
}
