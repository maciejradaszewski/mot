<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

/**
 * Class SiteSearchDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteSearchDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string */
    private $siteNumber;
    /** @var  string */
    private $siteName;
    /** @var  string  */
    private $siteTown;
    /** @var  string  */
    private $sitePostcode;
    /** @var  string */
    private $siteType;
    /** @var  string */
    private $siteVehicleClass;

    /**
     * @return string
     */
    public function getSiteNumber()
    {
        return $this->siteNumber;
    }

    /**
     * @param string $siteNumber
     * @return $this
     */
    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     * @return $this
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteTown()
    {
        return $this->siteTown;
    }

    /**
     * @param string $siteTown
     * @return $this
     */
    public function setSiteTown($siteTown)
    {
        $this->siteTown = $siteTown;
        return $this;
    }

    /**
     * @return string
     */
    public function getSitePostcode()
    {
        return $this->sitePostcode;
    }

    /**
     * @param string $sitePostcode
     * @return $this
     */
    public function setSitePostcode($sitePostcode)
    {
        $this->sitePostcode = $sitePostcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteType()
    {
        return $this->siteType;
    }

    /**
     * @param string $siteType
     * @return $this
     */
    public function setSiteType($siteType)
    {
        $this->siteType = $siteType;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteVehicleClass()
    {
        return $this->siteVehicleClass;
    }

    /**
     * @param string $siteVehicleClass
     * @return $this
     */
    public function setSiteVehicleClass($siteVehicleClass)
    {
        $this->siteVehicleClass = $siteVehicleClass;
        return $this;
    }
}
