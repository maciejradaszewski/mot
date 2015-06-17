<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * VehicleTestingStationEvidenceOfExclusiveUse
 *
 * @ORM\Table(
 * name="application_site_evidence_of_exclusive_use",
 * options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *  @ORM\Index(
 * name="fk_vts_evidence_of_exclusive_use_vts_details_id",
 * columns={"application_site_details_id"}
 * )
 * }
 * )
 * @ORM\Entity
 */
class VehicleTestingStationEvidenceOfExclusiveUse
{
    use CommonIdentityTrait;
    /**
     * @var \DvsaEntities\Entity\VehicleTestingStationDetails
     *
     * @ORM\OneToOne(
     * targetEntity="DvsaEntities\Entity\VehicleTestingStationDetails",
     * cascade={"persist"},
     * fetch="LAZY",
     * inversedBy="vehicleTestingStationEvidenceOfExclusiveUse"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(
     * name="application_site_details_id",
     * referencedColumnName="id",
     * nullable=false
     * )
     * })
     */
    private $vehicleTestingStationDetails = '';

    /**
     * @var boolean
     *
     * @ORM\Column(name="copy_of_land_registry", type="boolean", nullable=false)
     */
    private $copyOfLandRegistry;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proof_of_site_ownership", type="boolean", nullable=false)
     */
    private $proofOfSiteOwnership;

    /**
     * @var boolean
     *
     * @ORM\Column(name="copy_of_lease", type="boolean", nullable=false)
     */
    private $copyOfLease;

    /**
     * @var boolean
     *
     * @ORM\Column(name="exclusive_use_of_the_premises", type="boolean", nullable=false)
     */
    private $exclusiveUseOfThePremises;

    /**
     * @var string
     *
     * @ORM\Column(name="method_of_delivery", type="string", length=20, nullable=false)
     */
    private $methodOfDelivery;

    /**
     * @var boolean
     *
     * @ORM\Column(name="document_received", type="boolean", nullable=false)
     */
    private $documentReceived = false;

    /**
     * @param boolean $copyOfLandRegistry
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setCopyOfLandRegistry($copyOfLandRegistry)
    {
        $this->copyOfLandRegistry = $copyOfLandRegistry;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCopyOfLandRegistry()
    {
        return $this->copyOfLandRegistry;
    }

    /**
     * @param boolean $copyOfLease
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setCopyOfLease($copyOfLease)
    {
        $this->copyOfLease = $copyOfLease;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCopyOfLease()
    {
        return $this->copyOfLease;
    }

    /**
     * @param boolean $exclusiveUseOfThePremises
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setExclusiveUseOfThePremises($exclusiveUseOfThePremises)
    {
        $this->exclusiveUseOfThePremises = $exclusiveUseOfThePremises;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getExclusiveUseOfThePremises()
    {
        return $this->exclusiveUseOfThePremises;
    }

    /**
     * @param boolean $proofOfSiteOwnership
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setProofOfSiteOwnership($proofOfSiteOwnership)
    {
        $this->proofOfSiteOwnership = $proofOfSiteOwnership;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getProofOfSiteOwnership()
    {
        return $this->proofOfSiteOwnership;
    }

    /**
     * @param \DvsaEntities\Entity\VehicleTestingStationDetails $vehicleTestingStationDetails
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setVehicleTestingStationDetails($vehicleTestingStationDetails)
    {
        $this->vehicleTestingStationDetails = $vehicleTestingStationDetails;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\VehicleTestingStationDetails
     */
    public function getVehicleTestingStationDetails()
    {
        return $this->vehicleTestingStationDetails;
    }

    /**
     * @param string $methodOfDelivery
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setMethodOfDelivery($methodOfDelivery)
    {
        $this->methodOfDelivery = $methodOfDelivery;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodOfDelivery()
    {
        return $this->methodOfDelivery;
    }

    /**
     * @param boolean $documentReceived
     *
     * @return VehicleTestingStationEvidenceOfExclusiveUse
     */
    public function setDocumentReceived($documentReceived)
    {
        $this->documentReceived = $documentReceived;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDocumentReceived()
    {
        return $this->documentReceived;
    }
}
