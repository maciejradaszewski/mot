<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

/**
 * Class SiteTypeDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteTypeDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var string */
    private $type;

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
