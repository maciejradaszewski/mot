<?php

namespace DvsaCommon\Dto\Search;

/**
 * Class MotTestLogDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class MotTestSearchParamsDto extends SearchParamsDto
{
    /** @var int */
    private $organisationId;
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
    private $dateFromTS;
    /** @var int */
    private $dateToTS;
    /** @var string[] */
    private $status = [];
    /** @var string[] */
    private $testType = [];


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
    public function getDateFromTS()
    {
        return $this->dateFromTS;
    }

    /**
     * @param int $val
     *
     * return SearchParamsDto
     */
    public function setDateFromTS($val = null)
    {
        $this->dateFromTS = $val;

        return $this;
    }

    /**
     * @return int
     */
    public function getDateToTS()
    {
        return $this->dateToTS;
    }

    /**
     * @param int $val
     *
     * return SearchParamsDto
     */
    public function setDateToTS($val = null)
    {
        $this->dateToTS = $val;

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
}
