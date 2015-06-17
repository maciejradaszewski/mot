<?php

namespace DvsaCommon\Dto\Security;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;

/**
 * Class RoleStatusDto
 *
 * @package DvsaCommon\Dto\Security
 */
class RoleStatusDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;
    use EnumTypeDtoTrait;

    /** @var  string */
    private $name;

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
