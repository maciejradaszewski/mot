<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\EntityTrait\EnumType1EntityTrait;

/**
 * WeightSource entity
 *
 * @ORM\Table(name="weight_source_lookup")
 * @ORM\Entity(repositoryClass="\DvsaEntities\Repository\WeightSourceRepository")
 */
class WeightSource extends Entity
{
    use CommonIdentityTrait;
    use EnumType1EntityTrait;
}
