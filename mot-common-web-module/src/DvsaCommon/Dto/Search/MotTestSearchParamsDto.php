<?php

namespace DvsaCommon\Dto\Search;

use DvsaCommon\Constants\SearchParamConst;
use Zend\Stdlib\Parameters;

/**
 * Class MotTestLogDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class MotTestSearchParamsDto extends SearchParamsDto
{
    /** @var int */
    private $organisationId;
    /** @var int */
    private $siteId;
    /** @var string */
    private $siteNr;
    /** @var int */
    private $personId;
    /** @var int */
    private $vehicleId;
    /** @var string */
    private $vehicleVin;
    /** @var string */
    private $vehicleRegNr;
    /** @var int */
    private $dateFromTs;
    /** @var int */
    private $dateToTs;
    /** @var string[] */
    private $status = [];
    /** @var string[] */
    private $testType = [];
    /** @var string[] */
    private $testNumber;


    /**
     * @return int
     */
    public function getOrganisationId()
    {
        return $this->organisationId;
    }

    /**
     * @param int $organisationId
     *
     * @return $this
     */
    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     * @return $this
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteNr()
    {
        return $this->siteNr;
    }

    /**
     * @param string $siteNr
     *
     * @return $this
     */
    public function setSiteNr($siteNr)
    {
        $this->siteNr = $siteNr;

        return $this;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     *
     * @return $this
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;

        return $this;
    }

    /**
     * @return int
     */
    public function getVehicleId()
    {
        return $this->vehicleId;
    }

    /**
     * @param int $vehicleId
     *
     * @return $this
     */
    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;

        return $this;
    }

    /**
     * @return string
     */
    public function getVehicleRegNr()
    {
        return $this->vehicleRegNr;
    }

    /**
     * @param string $vehicleRegNr
     *
     * @return $this
     */
    public function setVehicleRegNr($vehicleRegNr)
    {
        $this->vehicleRegNr = $vehicleRegNr;

        return $this;
    }

    /**
     * @return string
     */
    public function getVehicleVin()
    {
        return $this->vehicleVin;
    }

    /**
     * @param string $vehicleVin
     *
     * @return $this
     */
    public function setVehicleVin($vehicleVin)
    {
        $this->vehicleVin = $vehicleVin;

        return $this;
    }

    /**
     * @return int
     */
    public function getDateFromTs()
    {
        return $this->dateFromTs;
    }

    /**
     * @param int $val
     *
     * return SearchParamsDto
     */
    public function setDateFromTs($val = null)
    {
        $this->dateFromTs = $val;

        return $this;
    }

    /**
     * @return int
     */
    public function getDateToTs()
    {
        return $this->dateToTs;
    }

    /**
     * @param int $val
     *
     * return SearchParamsDto
     */
    public function setDateToTs($val = null)
    {
        $this->dateToTs = $val;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string[] $statuses
     *
     * @return $this
     */
    public function setStatus($statuses)
    {
        $this->status = $statuses;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTestType()
    {
        return $this->testType;
    }

    /**
     * @param string[] $testType
     *
     * @return $this
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;

        return $this;
    }

    public function toQueryParams()
    {
        $params = new Parameters(
            parent::toQueryParams()->toArray()
            + array_filter(
                [
                    SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM => $this->getDateFromTs(),
                    SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM   => $this->getDateToTs(),
                ]
            )
        );

        return $params;
    }

    /**
     * @return \string[]
     */
    public function getTestNumber()
    {
        return $this->testNumber;
    }

    /**
     * @param \string[] $testNumber
     * @return MotTestSearchParamsDto
     */
    public function setTestNumber($testNumber)
    {
        $this->testNumber = $testNumber;
        return $this;
    }
}
