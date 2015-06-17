<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\EntityTrait\EnumType1EntityTrait;

/**
 * SiteType
 *
 * @ORM\Table(name="site_type")})
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\SiteTypeRepository")
 */
class SiteType
{
    use CommonIdentityTrait;
    use EnumType1EntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return SiteType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
