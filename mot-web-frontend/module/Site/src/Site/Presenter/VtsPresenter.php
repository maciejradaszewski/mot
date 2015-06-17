<?php

namespace Site\Presenter;

use Application\Service\CatalogService;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaClient\Entity\SitePosition;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Enum\PhoneContactTypeCode;

/**
 * Vehicle Testing Station Presenter
 */
class VtsPresenter
{
    /**
     * VTS data as an array
     * @var array
     */
    private $vtsData;

    /**
     * Catalog service so we can access the brake test types
     * @var CatalogService
     */
    private $catalog;

    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * Constructor
     * @param array $vtsData
     * @param CatalogService $catalog
     * @param MotFrontendAuthorisationServiceInterface $authorisationService
     */
    public function __construct(
        array $vtsData,
        CatalogService $catalog,
        MotFrontendAuthorisationServiceInterface $authorisationService
    ) {
        $this->vtsData = $vtsData;
        $this->catalog = $catalog;
        $this->authorisationService = $authorisationService;
    }

    /**
     * Return the ID of the VTS
     * @return int
     */
    public function getId()
    {
        return $this->vtsData['id'];
    }

    /**
     * Return the name of the VTS
     * @return string
     */
    public function getName()
    {
        return $this->vtsData['name'];
    }

    /**
     * Return the VTS number
     * @return string
     */
    public function getVtsNumber()
    {
        return $this->vtsData['siteNumber'];
    }

    /**
     * Get the VTS contact number
     * @return string
     */
    public function getPhoneNumber()
    {
        if (isset($this->vtsData['contacts'][0])) {
            $phones = $this->vtsData['contacts'][0]->getPhones();
            if (isset($phones[0])) {
                return $phones[0]->getNumber();
            }
        }

        return 'N/A';
    }

    /**
     * Get the VTS fax number
     * @return mixed
     */
    public function getFaxNumber()
    {
        if (isset($this->vtsData['contacts']) && isset($this->vtsData['contacts'][0])) {

            /** @var \DvsaClient\Entity\ContactDetail $primaryContact */
            $primaryContact = $this->vtsData['contacts'][0];

            return $primaryContact->getPrimaryFaxNumber() ?: 'N/A';
        }

        return 'N/A';
    }

    /**
     * Get the VTS email address
     * @return mixed
     */
    public function getEmailAddress()
    {
        if (isset($this->vtsData['contacts'][0])) {
            $emails = $this->vtsData['contacts'][0]->getEmails();
            if (isset($emails[0])) {
                return $emails[0]->getEmail();
            }
        }

        return 'N/A';
    }

    /**
     * Get the organisation id for a site
     * @return int
     */
    public function getOrganisationId()
    {
        return $this->vtsData['organisation']['id'];
    }

    /**
     * Get the organisation name for a site
     * @return string
     */
    public function getOrganisationName()
    {
        return $this->vtsData['organisation']['name'];
    }

    /**
     * Return facilities for a site
     * @return mixed
     */
    public function getFacilities()
    {
        return $this->vtsData['facilities'];
    }

    /**
     * Returns an array containing all pending and
     * accepted roles at the VTS
     * @return SitePosition[]
     */
    public function getPositions()
    {
        return $this->vtsData['positions'];
    }

    /**
     * Get the site opening hours objects
     * @return array
     */
    public function getOpeningHours()
    {
        return $this->vtsData['siteOpeningHours'];
    }

    /**
     * Returnt he opening hours for a day
     * @param SiteDailyOpeningHours $hours
     * @return string
     */
    public function displayOpeningHours(SiteDailyOpeningHours $hours)
    {
        if ($hours->isClosed()) {
            return 'Closed';
        }

        return DateTimeDisplayFormat::time($hours->getOpenTime()) .
        ' to ' . DateTimeDisplayFormat::time($hours->getCloseTime());
    }

    /**
     * Get the full address string with address lines separated with commas
     * @return string
     */
    public function getFullAddress()
    {
        if (empty($this->vtsData['address'])) {
            return "";
        }

        return join(
            ', ',
            array_filter(
                [
                    $this->vtsData['address']->getAddressLine1(),
                    $this->vtsData['address']->getAddressLine2(),
                    $this->vtsData['address']->getAddressLine3(),
                    $this->vtsData['address']->getTown(),
                    $this->vtsData['address']->getPostcode(),
                ]
            )
        );
    }

    /**
     * Get the classes that can be tested
     * @return string
     */
    public function getClasses()
    {
        if (isset($this->vtsData['roles']) && is_array($this->vtsData['roles']) && !empty($this->vtsData['roles'])) {
            sort($this->vtsData['roles']);
            return join(', ', $this->vtsData['roles']);
        }

        return 'N/A';
    }

    /**
     * Return the brake test type as a string
     * @param $code The brake test type code
     * @return string
     */
    public function getBrakeTestNameByTypeCode($code)
    {
        $brakeTestTypeCodeToNameMap = $this->catalog->getBrakeTestTypes();
        return isset($brakeTestTypeCodeToNameMap[$code]) ? $brakeTestTypeCodeToNameMap[$code] : '';
    }

    /**
     * Convenience to access full VTS data. Don't use in production
     * @return array
     */
    public function getVtsData()
    {
        return $this->vtsData;
    }

    public function canSearchVts()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::VEHICLE_TESTING_STATION_SEARCH);
    }
}
