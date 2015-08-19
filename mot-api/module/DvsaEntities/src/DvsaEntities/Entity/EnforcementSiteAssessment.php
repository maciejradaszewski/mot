<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="enforcement_site_assessment", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class EnforcementSiteAssessment extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Site", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     * })
     */
    private $vehicleTestingStation;

    /**
     * @var float
     *
     * @ORM\Column(name="site_assessment_score", type="decimal", precision=9, scale=2)
     */
    private $siteAssessmentScore;

    /**
     * @var \DvsaEntities\Entity\AuthorisationForAuthorisedExaminer
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\AuthorisationForAuthorisedExaminer", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="authorisation_for_authorised_examiner_id", referencedColumnName="id")
     * })
     */
    private $authorisedExaminer;

    /**
     * @var string
     *
     * @ORM\Column(name="ae_representative_name", type="string", length=100)
     */
    private $aeRepresentativeName;

    /**
     * @var string
     *
     * @ORM\Column(name="ae_representative_position", type="string", length=100)
     */
    private $aeRepresentativePosition;

    /**
     * @var \DvsaEntities\Entity\Person
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Person", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private $tester;

    /**
     * @var \DvsaEntities\Entity\EnforcementVisitOutcome
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\EnforcementVisitOutcome")
     * @ORM\JoinColumn(name="visit_outcome_id", referencedColumnName="id")
     */
    private $visitOutcome;

    /**
     * @var boolean
     *
     * @ORM\Column(name="advisory_issued", type="boolean")
     */
    private $advisoryIssued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_date", type="datetime", nullable=true)
     */
    private $visitDate;

    /**
     *  set Id
     *
     * @param int $id id
     *
     * @return EnforcementDecisionScore
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Site
     *
     * @param Site $vehicleTestingStation vts
     *
     * @return EnforcementSiteAssessment
     */
    public function setVehicleTestingStation($vehicleTestingStation)
    {
        $this->vehicleTestingStation = $vehicleTestingStation;

        return $this;
    }

    /**
     * Get Site
     *
     * @return Site
     */
    public function getVehicleTestingStation()
    {
        return $this->vehicleTestingStation;
    }

    /**
     *  set Score
     *
     * @param float $siteAssessmentScore score
     *
     * @return EnforcementSiteAssessment
     */
    public function setSiteAssessmentScore($siteAssessmentScore)
    {
        $this->siteAssessmentScore = $siteAssessmentScore;
        return $this;
    }

    /**
     * Get Score
     *
     * @return float
     */
    public function getSiteAssessmentScore()
    {
        return $this->siteAssessmentScore;
    }

    /**
     * Set the associated Authorised Examiner
     *
     * @param AuthorisationForAuthorisedExaminer $authorisedExaminer
     * @return EnforcementSiteAssessment
     */
    public function setAuthorisedExaminer($authorisedExaminer)
    {
        $this->authorisedExaminer = $authorisedExaminer;

        return $this;
    }

    /**
     * Get the associated Authorised Examiner
     *
     * @return AuthorisationForAuthorisedExaminer
     */
    public function getAuthorisedExaminer()
    {
        return $this->authorisedExaminer;
    }

    /**
     * Set AE/Representative name
     *
     * @param string $aeRepresentativeName aeRepresentativeName
     *
     * @return EnforcementSiteAssessment
     */
    public function setAeRepresentativeName($aeRepresentativeName)
    {
        $this->aeRepresentativeName = $aeRepresentativeName;

        return $this;
    }

    /**
     * Get AE/Representative name
     *
     * @return string
     */
    public function getAeRepresentativeName()
    {
        return $this->aeRepresentativeName;
    }

    /**
     * Set AE/Representative name
     *
     * @param string $aeRepresentativePosition aeRepresentativePosition
     *
     * @return EnforcementSiteAssessment
     */
    public function setAeRepresentativePosition($aeRepresentativePosition)
    {
        $this->aeRepresentativePosition = $aeRepresentativePosition;

        return $this;
    }

    /**
     * Get AE/Representative position
     *
     * @return string
     */
    public function getAeRepresentativePosition()
    {
        return $this->aeRepresentativePosition;
    }

    /**
     * Set Tester
     *
     * @param \DvsaEntities\Entity\Person $tester
     *
     * @return EnforcementSiteAssessment
     */
    public function setTester(\DvsaEntities\Entity\Person $tester)
    {
        $this->tester = $tester;

        return $this;
    }

    /**
     * Get Tester
     *
     * @return \DvsaEntities\Entity\Person
     */
    public function getTester()
    {
        return $this->tester;
    }

    /**
     * @param \DvsaEntities\Entity\EnforcementVisitOutcome $visitOutcome
     *
     * @return EnforcementSiteAssessment
     */
    public function setVisitOutcome($visitOutcome)
    {
        $this->visitOutcome = $visitOutcome;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\EnforcementVisitOutcome
     */
    public function getVisitOutcome()
    {
        return $this->visitOutcome;
    }

    /**
     *  set advisoryIssued
     *
     * @param int $advisoryIssued advisoryIssued
     *
     * @return EnforcementSiteAssessment
     */
    public function setAdvisoryIssued($advisoryIssued)
    {
        $this->advisoryIssued = $advisoryIssued;
        return $this;
    }

    /**
     * Get advisoryIssued
     *
     * @return int
     */
    public function getAdvisoryIssued()
    {
        return $this->advisoryIssued;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     * @return EnforcementSiteAssessment
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }
}
