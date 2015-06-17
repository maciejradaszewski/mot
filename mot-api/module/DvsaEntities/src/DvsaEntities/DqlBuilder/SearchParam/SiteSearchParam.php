<?php
namespace DvsaEntities\DqlBuilder\SearchParam;

use DvsaCommon\Dto\Search\SiteSearchParamsDto;
use DvsaCommonApi\Model\SearchParam;

/**
 * Class SiteSearchParam
 *
 * @package DvsaEntities\DqlBuilder\SearchParam
 */
class SiteSearchParam extends SearchParam
{
    const SEARCH_REQUIRED_DISPLAY_MESSAGE = 'You must search at least on one field to perform a search';

    const FIELD_SITE_NUMBER = 'site_number';
    const FIELD_SITE_NAME = 'site_name';
    const FIELD_SITE_TOWN = 'site_town';
    const FIELD_SITE_POSTCODE = 'site_postcode';
    const FIELD_SITE_VEHICLE_CLASS = 'site_vehicle_class';

    /** @var string $siteNumber */
    private $siteNumber;
    /** @var string $siteName */
    private $siteName;
    /** @var string $siteTown */
    private $siteTown;
    /** @var string $sitePostcode */
    private $sitePostcode;
    /** @var array $siteVehicleClass */
    private $siteVehicleClass;

    /**
     * @param SiteSearchParamsDto $dto
     *
     * @return $this
     */
    public function fromDto($dto)
    {
        if (!$dto instanceof SiteSearchParamsDto) {
            throw new \InvalidArgumentException(
                __METHOD__ . ' Expects instance of SiteSearchParamsDto, you passed ' . get_class($dto)
            );
        }

        parent::fromDto($dto);

        $this
            ->setSiteNumber($dto->getSiteNumber())
            ->setSiteName($dto->getSiteName())
            ->setSiteTown($dto->getSiteTown())
            ->setSitePostcode($dto->getSitePostcode())
            ->setSiteVehicleClass($dto->getSiteVehicleClass());

        return $this;
    }

    /**
     * Check if at least one of the searches have been filled
     *
     * @throws \UnexpectedValueException
     * @return $this
     */
    public function process()
    {
        if (!$this->checkValue($this->getSiteNumber())
            && !$this->checkValue($this->getSiteName())
            && !$this->checkValue($this->getSiteTown())
            && !$this->checkValue($this->getSitePostcode())) {

            throw new \UnexpectedValueException(
                self::SEARCH_REQUIRED_DISPLAY_MESSAGE
            );
        }
        return $this;
    }

    /**
     * Check if the value is empty and more than 2 characters length
     *
     * @param string $value
     * @return bool
     */
    private function checkValue($value)
    {
        return !empty(trim($value)) && strlen(trim($value)) > 2;
    }

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
}