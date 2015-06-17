<?php

namespace DvsaCommon\Dto\Vehicle;

/**
 * Class VehicleDto
 */
class VehicleDto extends AbstractVehicleDto
{
    /** @var int */
    private $year;

    /** @var CountryDto */
    private $countryOfRegistration;

    /** @var int */
    private $weight;
    /** @var string */
    private $weightSource;

    /** @var string */
    private $chassisNumber;

    /** @var int */
    private $noOfSeatBelts;
    /** @var \DateTime */
    private $seatBeltsLastChecked;

    /** @var \DateTime */
    private $amendedOn;

    private $freeTextMakeName;

    public function __construct()
    {
        $this->countryOfRegistration = new CountryDto();

        parent::__construct();
    }

    /**
     * @param int $year
     *
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }


    /**
     * @param CountryDto $countryOfRegistration
     *
     * @return $this
     */
    public function setCountryOfRegistration($countryOfRegistration)
    {
        $this->countryOfRegistration = $countryOfRegistration;

        return $this;
    }

    /**
     * @return CountryDto
     */
    public function getCountryOfRegistration()
    {
        return $this->countryOfRegistration;
    }


    /**
     * @param int $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $weightSource
     *
     * @return $this
     */
    public function setWeightSource($weightSource)
    {
        $this->weightSource = $weightSource;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeightSource()
    {
        return $this->weightSource;
    }


    /**
     * @param string $chassisNumber
     *
     * @return VehicleDto
     */
    public function setChassisNumber($chassisNumber)
    {
        $this->chassisNumber = $chassisNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getChassisNumber()
    {
        return $this->chassisNumber;
    }


    /**
     * @param int $noOfSeatBelts
     *
     * @return VehicleDto
     */
    public function setNoOfSeatBelts($noOfSeatBelts)
    {
        $this->noOfSeatBelts = $noOfSeatBelts;

        return $this;
    }

    /**
     * @return int
     */
    public function getNoOfSeatBelts()
    {
        return $this->noOfSeatBelts;
    }

    /**
     * @param \DateTime $date
     *
     * @return VehicleDto
     */
    public function setSeatBeltsLastChecked($date)
    {
        $this->seatBeltsLastChecked = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSeatBeltsLastChecked()
    {
        return $this->seatBeltsLastChecked;
    }


    /**
     * @param \DateTime $date
     *
     * @return VehicleDto
     */
    public function setAmendedOn($date)
    {
        $this->amendedOn = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAmendedOn()
    {
        return $this->amendedOn;
    }

    /**
     * @param string $freeTextMakeName
     * @return $this
     */
    public function setFreeTextMakeName($freeTextMakeName)
    {
        $this->freeTextMakeName = $freeTextMakeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFreeTextMakeName()
    {
        return $this->freeTextMakeName;
    }

    /**
     * {@inheritdoc}
     */
    public function isDvla()
    {
        return false;
    }
}
