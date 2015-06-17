<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Contact\ContactDto;

/**
 * Class SiteContactDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteContactDto extends ContactDto
{
    use CommonIdentityDtoTrait;

    private $type;

    /**
     * @param string $type
     *
     * @see SiteContactTypeCode
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getType()
    {
        return $this->type;
    }
}
