<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\EntityTrait\EnumType1EntityTrait;

/**
 * @ORM\Table(name="vehicle_class_group")
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\VehicleClassGroupRepository", readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="staticdata")
 */
class VehicleClassGroup extends Entity
{
    use CommonIdentityTrait;

    use EnumType1EntityTrait;
}
