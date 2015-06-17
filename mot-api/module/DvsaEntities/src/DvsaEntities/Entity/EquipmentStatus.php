<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\EntityTrait\EnumType1EntityTrait;

/**
 * @ORM\Table(name="equipment_status")
 * @ORM\Entity
 */
class EquipmentStatus extends Entity
{
    use CommonIdentityTrait;
    use EnumType1EntityTrait;
}
