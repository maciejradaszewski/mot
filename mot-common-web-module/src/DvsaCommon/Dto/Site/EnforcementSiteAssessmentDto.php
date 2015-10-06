<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class EnforcementSiteAssessmentDto
 *
 * @package DvsaCommon\Dto\Site
 */
class EnforcementSiteAssessmentDto extends AbstractDataTransferObject
{
    /**
     * @var int id
     */
    protected $id;

    /**
     * @var float siteAssessmentScore
     */
    protected $siteAssessmentScore;

    /**
     * @var string aeRepresentativeName
     */
    protected $aeRepresentativesFullName;

    /**
     * @var string aeRepresentativesRole
     */
    protected $aeRepresentativesRole;

    /**
     * @var string testerUserId
     */
    protected $testerUserId;

    /**
     * @var string $testerFullName
     */
    protected $testerFullName;

    /**
     * @var int aeRepresentativesUserId
     */
    protected $aeRepresentativesUserId;

    /**
     * @var string $dvsaExaminersUserId
     */
    protected $dvsaExaminersUserId;

    /**
     * @var string
     */
    protected $dvsaExaminersFullName;

    /**
     * @var string dateOfAssessment
     */
    protected $dateOfAssessment;

    /**
     * @var int siteId
     */
    protected $siteId;

    /**
     * @var int ae_organisation_id
     */
    protected $aeOrganisationId;

    /**
     * @var bool validateOnly
     */
    protected $validateOnly = false;

    /**
     * @var bool
     */
    protected $userIsNotAssessor;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getSiteAssessmentScore()
    {
        return $this->siteAssessmentScore;
    }

    /**
     * @param float $siteAssessmentScore
     * @return $this
     */
    public function setSiteAssessmentScore($siteAssessmentScore)
    {
        $this->siteAssessmentScore = $siteAssessmentScore;

        return $this;
    }

    /**
     * @return string
     */
    public function getAeRepresentativesFullName()
    {
        return $this->aeRepresentativesFullName;
    }

    /**
     * @param string $aeRepresentativeName
     * @return $this
     */
    public function setAeRepresentativesFullName($aeRepresentativeName)
    {
        $this->aeRepresentativesFullName = $aeRepresentativeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAeRepresentativesRole()
    {
        return $this->aeRepresentativesRole;
    }

    /**
     * @param string $aeRepresentativePosition
     * @return $this
     */
    public function setAeRepresentativesRole($aeRepresentativePosition)
    {
        $this->aeRepresentativesRole = $aeRepresentativePosition;

        return $this;
    }

    /**
     * @return string
     */
    public function getTesterUserId()
    {
        return $this->testerUserId;
    }

    /**
     * @param string $testerPersonId
     * @return $this
     */
    public function setTesterUserId($testerPersonId)
    {
        $this->testerUserId = $testerPersonId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAeRepresentativesUserId()
    {
        return $this->aeRepresentativesUserId;
    }

    /**
     * @param string $aeRepresentativesUserId
     * @return $this
     */
    public function setAeRepresentativesUserId($aeRepresentativesUserId)
    {
        $this->aeRepresentativesUserId = $aeRepresentativesUserId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDvsaExaminersUserId()
    {
        return $this->dvsaExaminersUserId;
    }

    /**
     * @param string $dvsaExaminersUserId
     * @return $this
     */
    public function setDvsaExaminersUserId($dvsaExaminersUserId)
    {
        $this->dvsaExaminersUserId = $dvsaExaminersUserId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateOfAssessment()
    {
        return $this->dateOfAssessment;
    }

    /**
     * @param string $dateOfAssessment
     * @return $this
     */
    public function setDateOfAssessment($dateOfAssessment)
    {
        $this->dateOfAssessment = $dateOfAssessment;

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
     * @return int
     */
    public function getAeOrganisationId()
    {
        return $this->aeOrganisationId;
    }

    /**
     * @param int $aeOrganisationId
     * @return $this
     */
    public function setAeOrganisationId($aeOrganisationId)
    {
        $this->aeOrganisationId = $aeOrganisationId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getValidateOnly()
    {
        return $this->validateOnly;
    }

    /**
     * @param boolean $validateOnly
     * @return $this
     */
    public function setValidateOnly($validateOnly)
    {
        $this->validateOnly = $validateOnly;

        return $this;
    }

    /**
     * @return string
     */
    public function getTesterFullName()
    {
        return $this->testerFullName;
    }

    /**
     * @param string $testerFullName
     * @return EnforcementSiteAssessmentDto
     */
    public function setTesterFullName($testerFullName)
    {
        $this->testerFullName = $testerFullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDvsaExaminersFullName()
    {
        return $this->dvsaExaminersFullName;
    }

    /**
     * @param string $dvsaExaminersFullName
     * @return EnforcementSiteAssessmentDto
     */
    public function setDvsaExaminersFullName($dvsaExaminersFullName)
    {
        $this->dvsaExaminersFullName = $dvsaExaminersFullName;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUserIsNotAssessor()
    {
        return $this->userIsNotAssessor;
    }

    /**
     * @param boolean $userIsNotAssessor
     * @return $this
     */
    public function setUserIsNotAssessor($userIsNotAssessor)
    {
        $this->userIsNotAssessor = $userIsNotAssessor;

        return $this;
    }

}
