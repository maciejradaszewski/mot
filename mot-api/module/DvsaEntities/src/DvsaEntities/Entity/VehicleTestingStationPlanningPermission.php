<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * VehicleTestingStationPlanningPermission
 *
 * @ORM\Table(
 * name="application_site_planning_permission",
 * options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *  @ORM\Index(
 * name="fk_vts_planning_permission_vts_details_id",
 * columns={"application_site_details_id"})
 * }
 * )
 * @ORM\Entity
 */
class VehicleTestingStationPlanningPermission
{
    use CommonIdentityTrait;

    /**
     * @var \DvsaEntities\Entity\VehicleTestingStationDetails
     *
     * @ORM\OneToOne(
     * targetEntity="DvsaEntities\Entity\VehicleTestingStationDetails",
     * cascade={"persist"},
     * fetch="LAZY",
     * inversedBy="vehicleTestingStationPlanningPermission"
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
     * @ORM\Column(name="planning_permission", type="boolean", nullable=false)
     */
    private $planningPermission;

    /**
     * @var boolean
     *
     * @ORM\Column(name="exemption_document", type="boolean", nullable=false)
     */
    private $exemptionDocument;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existing_approval", type="boolean", nullable=false)
     */
    private $existingApproval;

    /**
     * @var boolean
     *
     * @ORM\Column(name="solicitors_letter", type="boolean", nullable=false)
     */
    private $solicitorsLetter;

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
     * @param boolean $exemptionDocument
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
     */
    public function setExemptionDocument($exemptionDocument)
    {
        $this->exemptionDocument = $exemptionDocument;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getExemptionDocument()
    {
        return $this->exemptionDocument;
    }

    /**
     * @param boolean $existingApproval
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
     */
    public function setExistingApproval($existingApproval)
    {
        $this->existingApproval = $existingApproval;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getExistingApproval()
    {
        return $this->existingApproval;
    }

    /**
     * @param boolean $planningPermission
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
     */
    public function setPlanningPermission($planningPermission)
    {
        $this->planningPermission = $planningPermission;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPlanningPermission()
    {
        return $this->planningPermission;
    }

    /**
     * @param boolean $solicitorsLetter
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
     */
    public function setSolicitorsLetter($solicitorsLetter)
    {
        $this->solicitorsLetter = $solicitorsLetter;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSolicitorsLetter()
    {
        return $this->solicitorsLetter;
    }

    /**
     * @param \DvsaEntities\Entity\VehicleTestingStationDetails $vehicleTestingStationDetails
     *
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
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
     * @return \DvsaEntities\Entity\VehicleTestingStationPlanningPermission
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
     * @return VehicleTestingStationPlanningPermission
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
