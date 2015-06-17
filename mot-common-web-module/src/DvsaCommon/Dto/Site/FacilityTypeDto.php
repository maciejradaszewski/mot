<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\CommonTrait\EnumTypeDtoTrait;

/**
 * Class FacilityTypeDto
 *
 * @package DvsaCommon\Dto\Site
 */
class FacilityTypeDto extends AbstractDataTransferObject
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
