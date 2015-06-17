<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\Contact\ContactDto;

/**
 * Class OrganisationContactDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class OrganisationContactDto extends ContactDto
{
    private $type;

    /**
     * @param string $type
     *
     * @return OrganisationContactDto
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
