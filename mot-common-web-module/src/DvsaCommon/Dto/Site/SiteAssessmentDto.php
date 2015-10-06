<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Person\PersonDto;

class SiteAssessmentDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  VehicleTestingStationDto */
    private $site;
    /** @var  AuthorisedExaminerAuthorisationDto */
    private $authorisedExaminerAuthorisation;

    /** @var  string */
    private $representativeName;
    /** @var  string */
    private $representativePosition;

    /** @var  float */
    private $score;
    /** @var  PersonDto */
    private $tester;
    /** @var  boolean */
    private $isAdvisoryIssued;
    /** @var  string */
    private $visitDate;

    /**
     * @return VehicleTestingStationDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param VehicleTestingStationDto $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return AuthorisedExaminerAuthorisationDto
     */
    public function getAuthorisedExaminerAuthorisation()
    {
        return $this->authorisedExaminerAuthorisation;
    }

    /**
     * @param AuthorisedExaminerAuthorisationDto $authorisedExaminer
     *
     * @return $this
     */
    public function setAuthorisedExaminerAuthorisation($authorisedExaminer)
    {
        $this->authorisedExaminerAuthorisation = $authorisedExaminer;
        return $this;
    }

    public function getRepresentativeName()
    {
        return $this->representativeName;
    }

    /**
     * @return $this
     */
    public function setRepresentativeName($representativeName)
    {
        $this->representativeName = $representativeName;
        return $this;
    }

    public function getRepresentativePosition()
    {
        return $this->representativePosition;
    }

    /**
     * @return $this
     */
    public function setRepresentativePosition($representativePosition)
    {
        $this->representativePosition = $representativePosition;
        return $this;
    }

    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return PersonDto
     */
    public function getTester()
    {
        return $this->tester;
    }

    /**
     * @param PersonDto $tester
     *
     * @return $this
     */
    public function setTester($tester)
    {
        $this->tester = $tester;
        return $this;
    }

    public function isAdvisoryIssued()
    {
        return $this->isAdvisoryIssued;
    }

    /**
     * @return $this
     */
    public function setIsAdvisoryIssued($advisoryIssued)
    {
        $this->isAdvisoryIssued = $advisoryIssued;
        return $this;
    }

    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * @return $this
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;
        return $this;
    }
}
