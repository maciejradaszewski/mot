<?php

namespace DvsaCommon\Dto\Search;

use Zend\Stdlib\Parameters;
use DvsaCommon\Constants\SearchParamConst;

/**
 * Class SiteSearchParamsDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class SiteSearchParamsDto extends SearchParamsDto
{
    const SITE_NUMBER = 'site_number';
    const SITE_NAME = 'site_name';
    const SITE_TOWN = 'site_town';
    const SITE_POSTCODE = 'site_postcode';
    const SITE_VEHICLE_CLASS = 'site_vehicle_class';

    /** @var string */
    private $siteNumber;
    /** @var string */
    private $siteName;
    /** @var string */
    private $siteTown;
    /** @var string */
    private $sitePostcode;
    /** @var array */
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
     * @return array
     */
    public function getSiteVehicleClass()
    {
        return $this->siteVehicleClass;
    }

    /**
     * @param array $siteVehicleClass
     * @return $this
     */
    public function setSiteVehicleClass($siteVehicleClass)
    {
        $this->siteVehicleClass = $siteVehicleClass;
        return $this;
    }

    public function toQueryParams()
    {
        return new Parameters(
            array_filter(
                [
                    SearchParamConst::ROW_COUNT => $this->getRowsCount(),
                    SearchParamConst::PAGE_NR => $this->getPageNr(),
                    SearchParamConst::SORT_BY => $this->getSortBy(),
                    SearchParamConst::SORT_DIRECTION => $this->getSortDirection(),
                    self::SITE_NUMBER => $this->getSiteNumber(),
                    self::SITE_NAME => $this->getSiteName(),
                    self::SITE_TOWN => $this->getSiteTown(),
                    self::SITE_POSTCODE => $this->getSitePostcode(),
                    self::SITE_VEHICLE_CLASS => $this->getSiteVehicleClass(),
                ]
            )
        );
    }
}
