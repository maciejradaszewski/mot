<?php

namespace Site\ViewModel;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaCommon\Dto\Search\SiteSearchParamsDto;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class SiteSearchViewModel
 * @package Site\ViewModel
 */
class SiteSearchViewModel extends AbstractFormModel
{
    const FIELD_SITE_NUMBER = 'site_number';
    const FIELD_SITE_NAME = 'site_name';
    const FIELD_SITE_TOWN = 'site_town';
    const FIELD_SITE_POSTCODE = 'site_postcode';
    const FIELD_SITE_VEHICLE_CLASS = 'site_vehicle_class';

    const NO_RESULT_FOUND = 'No results found';
    const ONE_FIELD_REQUIRED = 'You need to enter some search criteria';
    const ONLY_VEHICLE_CLASS = 'You cannot search by vehicle classes alone - try expanding your search criteria';

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

    /** @var SiteListDto */
    private $siteList;

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


    /**
     * @param int $class
     * @return bool
     */
    public function isSiteVehicleClassChecked($class)
    {
        if (!empty($this->siteVehicleClass)) {
            return in_array($class, $this->siteVehicleClass);
        }
        return false;
    }

    /**
     * @return array
     */
    public function getSiteVehicleClassParameters()
    {
        return [
            [
                'value'     => '1',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 1',
                'checked'   => $this->isSiteVehicleClassChecked(1),
            ],
            [
                'value'     => '2',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 2',
                'checked'   => $this->isSiteVehicleClassChecked(2),
            ],
            [
                'value'     => '3',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 3',
                'checked'   => $this->isSiteVehicleClassChecked(3),
            ],
            [
                'value'     => '4',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 4',
                'checked'   => $this->isSiteVehicleClassChecked(4),
            ],
            [
                'value'     => '5',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 5',
                'checked'   => $this->isSiteVehicleClassChecked(5),
            ],
            [
                'value'     => '7',
                'inputName' => SiteSearchViewModel::FIELD_SITE_VEHICLE_CLASS . '[]',
                'key'       => 'Class 7',
                'checked'   => $this->isSiteVehicleClassChecked(7),
            ],
        ];
    }

    /**
     * Check if at least one search filed is field
     *
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->getSiteNumber())
            && empty($this->getSiteName())
            && empty($this->getSiteTown())
            && empty($this->getSitePostcode())) {

            if (empty($this->getSiteVehicleClass())) {
                $this->addError(self::FIELD_SITE_NUMBER, self::ONE_FIELD_REQUIRED);
            } else {
                $this->addError(self::FIELD_SITE_NUMBER, self::ONLY_VEHICLE_CLASS);
            }
            return false;
        }

        return !$this->hasErrors();
    }

    /**
     * @param array $postData
     *
     * @return $this
     */
    public function populateFromPost(array $postData)
    {
        $this->setSiteNumber(ArrayUtils::tryGet($postData, self::FIELD_SITE_NUMBER));
        $this->setSiteName(ArrayUtils::tryGet($postData, self::FIELD_SITE_NAME));
        $this->setSiteTown(ArrayUtils::tryGet($postData, self::FIELD_SITE_TOWN));
        $this->setSitePostcode(ArrayUtils::tryGet($postData, self::FIELD_SITE_POSTCODE));
        $this->setSiteVehicleClass(ArrayUtils::tryGet($postData, self::FIELD_SITE_VEHICLE_CLASS));

        return $this;
    }

    /**
     * @return SiteSearchParamsDto
     */
    public function prepareSearchParams()
    {
        return (new SiteSearchParamsDto())
            ->setSiteNumber($this->getSiteNumber())
            ->setSiteName($this->getSiteName())
            ->setSiteTown($this->getSiteTown())
            ->setSitePostcode($this->getSitePostcode())
            ->setSiteVehicleClass($this->getSiteVehicleClass());
    }

    /**
     * @param SiteListDto $siteList
     * @return $this
     */
    public function setSiteList(SiteListDto $siteList)
    {
        $this->siteList = $siteList;
        return $this;
    }

    /**
     * @return \DvsaCommon\Dto\Site\SiteSearchDto[]
     */
    public function getSites()
    {
        return $this->siteList->getSites();
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->siteList->getTotalResult();
    }

    /**
     * @return string
     */
    public function displaySearchCriteria()
    {
        return implode(
            ', ',
            array_filter(
                [
                    $this->getSiteNumber(),
                    $this->getSiteName(),
                    $this->getSiteTown(),
                    $this->getSitePostcode(),
                ],
                'strlen'
            )
        );
    }

    /**
     * @return string
     */
    public function getSearchPage()
    {
        return SiteUrlBuilderWeb::search();
    }

    /**
     * @return string
     */
    public function getResultPage()
    {
        return SiteUrlBuilderWeb::result();
    }
}
