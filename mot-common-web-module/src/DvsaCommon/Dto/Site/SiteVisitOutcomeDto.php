<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

class SiteVisitOutcomeDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string */
    private $description;
    /** @var  integer */
    private $position;

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}
