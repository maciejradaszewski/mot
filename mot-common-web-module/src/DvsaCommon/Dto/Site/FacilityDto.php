<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

/**
 * Class FacilityDto
 *
 * @package DvsaCommon\Dto\Site
 */
class FacilityDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  SiteDto */
    private $site;
    /** @var  string */
    private $name;
    /** @var  FacilityTypeDto */
    private $type;

    /**
     * @return SiteDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param SiteDto $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return string
     */
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

    /**
     * @return FacilityTypeDto
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param FacilityTypeDto $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
