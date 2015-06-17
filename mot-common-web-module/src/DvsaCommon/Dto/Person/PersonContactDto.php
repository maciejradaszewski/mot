<?php

namespace DvsaCommon\Dto\Person;

use DvsaCommon\Dto\Contact\ContactDto;

/**
 * Class PersonContactDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class PersonContactDto extends ContactDto
{
    private $type;

    /**
     * @param string $type
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
