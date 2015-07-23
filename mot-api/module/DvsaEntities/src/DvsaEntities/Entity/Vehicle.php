<?php

namespace DvsaEntities\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * Vehicle.
 *
 * @ORM\Table(
 *  name="vehicle",
 *  indexes={
 *      @ORM\Index(name="vehicle_vin_and_registration", columns={"vin", "registration"}),
 *      @ORM\Index(name="vehicle_registration", columns={"registration"}),
 *      @ORM\Index(name="fk_vehicle_vehicle_class_id", columns={"vehicle_class_id"}),
 *      @ORM\Index(name="fk_vehicle_model_detail_id", columns={"model_detail_id"}),
 *      @ORM\Index(name="fk_vehicle_country_of_registration_id", columns={"country_of_registration_id"}),
 *      @ORM\Index(name="fk_vehicle_transmission_type_id", columns={"transmission_type_id"}),
 *      @ORM\Index(name="fk_vehicle_created_by", columns={"created_by"}),
 *      @ORM\Index(name="fk_vehicle_last_updated_by", columns={"last_updated_by"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\VehicleRepository")
 */
class Vehicle extends Entity implements VehicleInterface
{
    use CommonIdentityTrait;

    const ENTITY_NAME = 'Vehicle';

    const VEHICLE_CLASS_1 = VehicleClassCode::CLASS_1;
    const VEHICLE_CLASS_2 = VehicleClassCode::CLASS_2;
    const VEHICLE_CLASS_3 = VehicleClassCode::CLASS_3;
    const VEHICLE_CLASS_4 = VehicleClassCode::CLASS_4;
    const VEHICLE_CLASS_5 = VehicleClassCode::CLASS_5;
    const VEHICLE_CLASS_7 = VehicleClassCode::CLASS_7;

    /**
     * @var string
     *
     * @ORM\Column(name="registration", type="string", length=20, nullable=true)
     */
    private $registration;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=30, nullable=true)
     */
    private $vin;

    /**
     * @var integer
     *
     * @ORM\Column(name="empty_vrm_reason_id", type="integer", nullable=true)
     */

    /**
     * @var EmptyVrmReason
     *
     * @ORM\ManyToOne(targetEntity="EmptyVrmReason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empty_vrm_reason_id", referencedColumnName="id")
     * })
     */
    private $emptyVrmReason;

    /**
     * @var EmptyVinReason
     *
     * @ORM\ManyToOne(targetEntity="EmptyVinReason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empty_vin_reason_id", referencedColumnName="id")
     * })
     */
    private $emptyVinReason;

    /**
     * @var \integer
     *
     * @ORM\Column(name="year", type="integer", length=4, nullable=true)
     */
    private $year;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="manufacture_date", type="datetime", nullable=true)
     */
    private $manufactureDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_registration_date", type="date", nullable=true)
     */
    private $firstRegistrationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_used_date", type="date", nullable=true)
     */
    private $firstUsedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="cylinder_capacity", type="integer", nullable=true)
     */
    private $cylinderCapacity;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id"),
     * })
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="model_name", type="string", nullable=true)
     */
    private $modelName;

    /**
     * @var Make
     *
     * @ORM\ManyToOne(targetEntity="Make", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="make_id", referencedColumnName="id"),
     * })
     */
    private $make;

    /**
     * @var string
     *
     * @ORM\Column(name="make_name", type="string", nullable=true)
     */
    private $makeName;

    /**
     * @var ModelDetail
     *
     * @ORM\ManyToOne(targetEntity="ModelDetail", fetch="EAGER")
     * @ORM\JoinColumn(name="model_detail_id", referencedColumnName="id", nullable=true)
     */
    private $modelDetail;

    /**
     * @var VehicleClass
     *
     * @ORM\ManyToOne(targetEntity="VehicleClass", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicle_class_id", referencedColumnName="id")
     * })
     */
    private $vehicleClass;

    /**
     * @var Colour
     *
     * @ORM\ManyToOne(targetEntity="Colour", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="primary_colour_id", referencedColumnName="id")
     * })
     */
    private $colour;

    /**
     * @var Colour
     *
     * @ORM\ManyToOne(targetEntity="Colour", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="secondary_colour_id", referencedColumnName="id")
     * })
     */
    private $secondaryColour;

    /**
     * @var FuelType
     *
     * @ORM\ManyToOne(targetEntity="FuelType", fetch="EAGER")
     * @ORM\JoinColumn(name="fuel_type_id", referencedColumnName="id")
     */
    private $fuelType;

    /**
     * @var BodyType
     *
     * @ORM\ManyToOne(targetEntity="BodyType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="body_type_id", referencedColumnName="id")
     * })
     */
    private $bodyType;

    /**
     * @var CountryOfRegistration
     *
     * @ORM\ManyToOne(targetEntity="CountryOfRegistration", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_of_registration_id", referencedColumnName="id")
     * })
     */
    private $countryOfRegistration;

    /**
     * @var TransmissionType
     *
     * @ORM\ManyToOne(targetEntity="TransmissionType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transmission_type_id", referencedColumnName="id")
     * })
     */
    private $transmissionType;

    /**
     * VSI weight for brake tests.
     *
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var WeightSource
     *
     * @ORM\ManyToOne(targetEntity="WeightSource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="weight_source_id", referencedColumnName="id")
     * })
     */
    private $weightSource;

    /**
     * @var string
     *
     * @ORM\Column(name="chassis_number", type="string", nullable=true)
     */
    private $chassisNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="engine_number", type="string", nullable=true)
     */
    private $engineNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="seating_capacity", type="integer", nullable=true)
     */
    private $seatingCapacity;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_seat_belts", type="integer", nullable=true)
     */
    private $noOfSeatBelts = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="seat_belts_last_checked", type="date", nullable=true)
     */
    private $seatBeltsLastChecked;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_new_at_first_reg", type="smallint", nullable=true)
     */
    private $newAtFirstReg = 0;

    /**
     * @ORM\OneToMany(targetEntity="DvsaEntities\Entity\VehicleV5C", mappedBy="vehicle")
     */
    private $vehicleV5Cs;

    /**
     * Unique DVLA reference.
     *
     * @var int
     *
     * @ORM\Column(name="dvla_vehicle_id", type="integer", length=11, nullable=true)
     */
    private $dvlaVehicleId;

    public function __construct()
    {
        $this->vehicleV5Cs = new ArrayCollection();
    }

    /**
     * @param Colour $colour
     *
     * @return $this
     */
    public function setColour(Colour $colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Colour
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * @param CountryOfRegistration $countryOfRegistration
     *
     * @return $this
     */
    public function setCountryOfRegistration(CountryOfRegistration $countryOfRegistration)
    {
        $this->countryOfRegistration = $countryOfRegistration;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\CountryOfRegistration
     */
    public function getCountryOfRegistration()
    {
        return $this->countryOfRegistration;
    }

    /**
     * @param int $cylinderCapacity
     *
     * @return $this
     */
    public function setCylinderCapacity($cylinderCapacity)
    {
        $this->cylinderCapacity = $cylinderCapacity;

        return $this;
    }

    /**
     * @return int
     */
    public function getCylinderCapacity()
    {
        return $this->cylinderCapacity;
    }

    /**
     * @param \DateTime $date
     *
     * @return Vehicle
     */
    public function setManufactureDate($date)
    {
        $this->manufactureDate = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getManufactureDate()
    {
        return $this->manufactureDate;
    }

    /**
     * @param \DateTime $date
     *
     * @return Vehicle
     */
    public function setFirstRegistrationDate($date)
    {
        $this->firstRegistrationDate = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFirstRegistrationDate()
    {
        return $this->firstRegistrationDate;
    }

    /**
     * @param \DateTime $firstUsedDate
     *
     * @return Vehicle
     */
    public function setFirstUsedDate(\DateTime $firstUsedDate)
    {
        $this->firstUsedDate = $firstUsedDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFirstUsedDate()
    {
        return $this->firstUsedDate;
    }

    /**
     * @param FuelType $fuelType
     *
     * @return $this
     */
    public function setFuelType(FuelType $fuelType = null)
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * @return FuelType
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * @param BodyType $bodyType
     *
     * @return $this
     */
    public function setBodyType(BodyType $bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\BodyType
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @param $makeName string
     */
    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;
        return $this;
    }

    public function getMakeName()
    {
        return $this->getMake() ? $this->getMake()->getName() : $this->makeName;
    }

    public function setFreeTextMakeName($makeName)
    {
        $this->makeName = $makeName;
        return $this;
    }

    public function getFreeTextMakeName()
    {
        return $this->makeName;
    }

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * NOTE: If the model name is the only property required use getModelName() as it provides a safer approach.
     *
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getModelName()
    {
        return $this->getModel() ? $this->getModel()->getName() : $this->modelName;
    }

    /**
     * @param Make $make
     *
     * @return $this
     */
    public function setMake(Make $make = null)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * When retrieving the Make via the Vehicle class fallback to the model table if a Make ref is not found. Ideally
     * we should either be using Vehicle.getMake() or Model.getMake() but not both.
     *
     * @return Make|null
     */
    public function getMake()
    {
        return $this->make ?: ($this->getModel() ? $this->getModel()->getMake() : null);
    }

    public function setFreeTextModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * @param ModelDetail $modelDetail
     *
     * @return $this
     */
    public function setModelDetail(ModelDetail $modelDetail = null)
    {
        $this->modelDetail = $modelDetail;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\ModelDetail
     */
    public function getModelDetail()
    {
        return $this->modelDetail;
    }

    /**
     * @param string $registration
     *
     * @return $this
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param Colour $secondaryColour
     *
     * @return $this
     */
    public function setSecondaryColour(Colour $secondaryColour = null)
    {
        $this->secondaryColour = $secondaryColour;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Colour
     */
    public function getSecondaryColour()
    {
        return $this->secondaryColour;
    }

    /**
     * @param TransmissionType $transmissionType
     *
     * @return $this
     */
    public function setTransmissionType(TransmissionType $transmissionType)
    {
        $this->transmissionType = $transmissionType;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\TransmissionType
     */
    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    /**
     * @param \DvsaEntities\Entity\VehicleClass $vehicleClass
     *
     * @return Vehicle
     */
    public function setVehicleClass(VehicleClass $vehicleClass)
    {
        $this->vehicleClass = $vehicleClass;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\VehicleClass
     */
    public function getVehicleClass()
    {
        return $this->vehicleClass;
    }

    /**
     * @param string $vin
     *
     * @return $this
     */
    public function setVin($vin)
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * @return string
     */
    public function getVin()
    {
        return $this->vin;
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
     * @return \integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param integer $weight weight in kilos
     *
     * @return Vehicle
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return integer weight in kilos
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param WeightSource $weightSource
     *
     * @return Vehicle
     */
    public function setWeightSource($weightSource)
    {
        $this->weightSource = $weightSource;

        return $this;
    }

    /**
     * @return WeightSource
     */
    public function getWeightSource()
    {
        return $this->weightSource;
    }

    /**
     * @param string $chassisNumber
     *
     * @return Vehicle
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
     * @param string $engineNumber
     *
     * @return Vehicle
     */
    public function setEngineNumber($engineNumber)
    {
        $this->engineNumber = $engineNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getEngineNumber()
    {
        return $this->engineNumber;
    }

    /**
     * @param integer $seatingCapacity
     *
     * @return Vehicle
     */
    public function setSeatingCapacity($seatingCapacity)
    {
        $this->seatingCapacity = $seatingCapacity;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSeatingCapacity()
    {
        return $this->seatingCapacity;
    }

    /**
     * @param integer $noOfSeatBelts
     *
     * @return Vehicle
     */
    public function setNoOfSeatBelts($noOfSeatBelts)
    {
        $this->noOfSeatBelts = $noOfSeatBelts;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNoOfSeatBelts()
    {
        return $this->noOfSeatBelts;
    }

    /**
     * @param \DateTime $date
     *
     * @return Vehicle
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
     * @param integer $value
     *
     * @return Vehicle
     */
    public function setNewAtFirstReg($value)
    {
        $this->newAtFirstReg = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getNewAtFirstReg()
    {
        return $this->newAtFirstReg;
    }

    /**
     * @return bool
     */
    public function isVehicleNewAtFirstRegistration()
    {
        return (bool) $this->newAtFirstReg;
    }

    /**
     * @param int $dvlaVehicleId
     *
     * @return $this
     */
    public function setDvlaVehicleId($dvlaVehicleId)
    {
        $this->dvlaVehicleId = $dvlaVehicleId;

        return $this;
    }

    /**
     * @return int
     */
    public function getDvlaVehicleId()
    {
        return $this->dvlaVehicleId;
    }

    /**
     * @param EmptyVrmReason $reason
     * @return $this
     */
    public function setEmptyVrmReason($reason)
    {
        $this->emptyVrmReason = $reason;
        return $this;
    }

    /**
     * @return EmptyVrmReason
     */
    public function getEmptyVrmReason()
    {
        return $this->emptyVrmReason;
    }

    /**
     * @param EmptyVinReason $reason
     * @return $this
     */
    public function setEmptyVinReason($reason)
    {
        $this->emptyVinReason = $reason;
        return $this;
    }

    /**
     * @return EmptyVinReason
     */
    public function getEmptyVinReason()
    {
        return $this->emptyVinReason;
    }

    /**
     * Indicate based on instantiation the nature of this vehicles origins.
     *
     * @return bool
     */
    public function isDvla()
    {
        return false;
    }
}
