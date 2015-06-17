<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\EntityTrait\EnumType1EntityTrait;

/**
 * OrganisationContactType
 *
 * @ORM\Table(name="organisation_contact_type")
 * @ORM\Entity(repositoryClass="\DvsaEntities\Repository\OrganisationContactTypeRepository")
 */
class OrganisationContactType
{
    use CommonIdentityTrait;
    use EnumType1EntityTrait;
}
