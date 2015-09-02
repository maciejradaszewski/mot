<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaMotApi\Helper\Odometer\OdometerHolderInterface;

/**
 * ReplacementCertificateDraft
 *
 * @ORM\Table(name="replacement_certificate_draft",
 * options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\ReplacementCertificateDraftRepository")
 */
class ReplacementCertificateDraft extends Entity implements OdometerHolderInterface
{
    use CommonIdentityTrait;

    /**
     * @var \DvsaEntities\Entity\MotTest
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\MotTest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mot_test_id", referencedColumnName="id")
     * })
     */
    private $motTest;

    /**
     * @var OdometerReading
     *
     * @ORM\OneToOne(targetEntity="DvsaEntities\Entity\OdometerReading", cascade={"persist"})
     * @ORM\JoinColumn(name="odometer_reading_id", referencedColumnName="id")
     */

    private $odometerReading;
    /**
     * @var string
     *
     * @ORM\Column(name="vrm", type="string", length=20, nullable=true)
     */
    private $vrm;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=30, nullable=true)
     */
    private $vin;


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
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="replacement_reason", type="text", nullable=true)
     */
    private $reasonForReplacement;

    /**
     * @var \DvsaEntities\Entity\CertificateChangeDifferentTesterReason
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\CertificateChangeDifferentTesterReason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="different_tester_reason_id", referencedColumnName="id")
     * })
     */
    private $reasonForDifferentTester;

    /**
     * @var Make
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Make")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="make_id", referencedColumnName="id")
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
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Model")
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
     * @var CountryOfRegistration
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\CountryOfRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_of_registration_id", referencedColumnName="id")
     * })
     */
    private $countryOfRegistration;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicle_testing_station_id", referencedColumnName="id")
     * })
     */
    private $vehicleTestingStation;

    /**
     * @var Colour
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Colour")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="primary_colour_id", referencedColumnName="id")
     * })
     */
    private $primaryColour;

    /**
     * @var Colour
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Colour")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="secondary_colour_id", referencedColumnName="id")
     * })
     */
    private $secondaryColour;

    /**
     * @var $motTestVersion
     *
     * @ORM\Column(name="mot_test_version", type="integer", length=11, nullable=false)
     */
    private $motTestVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="is_vin_registration_changed", type="integer", length=1, nullable=true)
     */
    private $isVinRegistrationChanged;


    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param mixed $motTestVersion
     *
     * @return $this
     */
    public function setMotTestVersion($motTestVersion)
    {
        $this->motTestVersion = $motTestVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMotTestVersion()
    {
        return $this->motTestVersion;
    }

    /**
     * @param \DvsaEntities\Entity\CertificateChangeDifferentTesterReason $reasonForDifferentTester
     *
     * @return $this
     */
    public function setReasonForDifferentTester($reasonForDifferentTester)
    {
        $this->reasonForDifferentTester = $reasonForDifferentTester;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\CertificateChangeDifferentTesterReason
     */
    public function getReasonForDifferentTester()
    {
        return $this->reasonForDifferentTester;
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
     * @return CountryOfRegistration
     */
    public function getCountryOfRegistration()
    {
        return $this->countryOfRegistration;
    }

    /**
     * @param string $vrm
     *
     * @return $this
     */
    public function setVrm($vrm)
    {
        $this->vrm = $vrm;
        return $this;
    }

    /**
     * @return string
     */
    public function getVrm()
    {
        return $this->vrm;
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
     * @param integer $isVinRegistrationChanged
     *
     * @return $this
     */
    public function setIsVinRegistrationChanged($isVinRegistrationChanged)
    {
        $this->isVinRegistrationChanged = $isVinRegistrationChanged;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIsVinRegistrationChanged()
    {
        return $this->isVinRegistrationChanged;
    }

    /**
     * @param \DvsaEntities\Entity\Site $vehicleTestingStation
     *
     * @return $this
     */
    public function setVehicleTestingStation($vehicleTestingStation)
    {
        $this->vehicleTestingStation = $vehicleTestingStation;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Site
     */
    public function getVehicleTestingStation()
    {
        return $this->vehicleTestingStation;
    }

    /**
     * @param \DvsaEntities\Entity\Colour $secondaryColour
     *
     * @return $this
     */
    public function setSecondaryColour($secondaryColour)
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
     * @param string $reasonForReplacement
     *
     * @return $this
     */
    public function setReplacementReason($reasonForReplacement)
    {
        $this->reasonForReplacement = $reasonForReplacement;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplacementReason()
    {
        return $this->reasonForReplacement;
    }

    /**
     * @param \DvsaEntities\Entity\Colour $primaryColour
     *
     * @return $this
     */
    public function setPrimaryColour($primaryColour)
    {
        $this->primaryColour = $primaryColour;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Colour
     */
    public function getPrimaryColour()
    {
        return $this->primaryColour;
    }

    /**
     * @param \DvsaEntities\Entity\OdometerReading $odometerReading
     *
     * @return $this
     */
    public function setOdometerReading($odometerReading)
    {
        $this->odometerReading = $odometerReading;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\OdometerReading
     */
    public function getOdometerReading()
    {
        return $this->odometerReading;
    }

    /**
     * @param \DvsaEntities\Entity\MotTest $motTest
     *
     * @return $this
     */
    public function setMotTest($motTest)
    {
        $this->motTest = $motTest;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\MotTest
     */
    public function getMotTest()
    {
        return $this->motTest;
    }

    /**
     * @param \DvsaEntities\Entity\Model $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \DvsaEntities\Entity\Make $make
     *
     * @return $this
     */
    public function setMake($make)
    {
        $this->make = $make;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Make
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param \DateTime $expiryDate
     *
     * @return $this
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return EmptyVrmReason
     */
    public function getEmptyVrmReason()
    {
        return $this->emptyVrmReason;
    }

    /**
     * @param EmptyVrmReason $emptyVrmReason
     * @return $this
     */
    public function setEmptyVrmReason($emptyVrmReason)
    {
        $this->emptyVrmReason = $emptyVrmReason;
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
     * @param EmptyVinReason $emptyVinReason
     * @return $this
     */
    public function setEmptyVinReason($emptyVinReason)
    {
        $this->emptyVinReason = $emptyVinReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getMakeName()
    {
        return $this->makeName;
    }

    /**
     * @param $makeName
     * @return $this
     */
    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param $modelName
     * @return $this
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
        return $this;
    }
}
