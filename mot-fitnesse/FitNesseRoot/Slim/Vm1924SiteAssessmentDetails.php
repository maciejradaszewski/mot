<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Class Vm1924SiteAssessmentDetails
 */
class Vm1924SiteAssessmentDetails
{
    protected $assessmentId;

    public function setId($id)
    {
        $this->assessmentId = $id;
    }

    public function success()
    {
        $urlBuilder = (new UrlBuilder())->enforcementSiteAssessmentLoad()->routeParam('id', $this->assessmentId);
        $jsonResult = TestShared::execCurlForJsonFromUrlBuilder(new \MotFitnesse\Util\FtEnfTesterCredentialsProvider(), $urlBuilder);
        $this->data = $jsonResult['data'];

        return true;
    }

    public function siteScore()
    {
        return $this->data['siteAssessmentScore'];
    }

    public function aeRepName()
    {
        return $this->data['aeRepresentativeName'];
    }

    public function aeRepPosition()
    {
        return $this->data['aeRepresentativePosition'];
    }

    public function aeExaminerId()
    {
        return $this->data['authorisedExaminer']['id'];
    }

    public function advisoryIssued()
    {
        return $this->data['advisoryIssued'];
    }

    public function visitDate()
    {
        return $this->data['visitDate'];
    }

    public function createdBy()
    {
        return $this->data['createdBy'];
    }

    public function createdOn()
    {
        return $this->data['createdOn'];
    }

    public function lastUpdatedBy()
    {
        return $this->data['lastUpdatedBy'];
    }

    public function lastUpdatedOn()
    {
        return $this->data['lastUpdatedOn'];
    }

    public function siteNumber()
    {
        return $this->data['vehicleTestingStation']['siteNumber'];
    }

    public function visitOutcome()
    {
        return $this->data['visitOutcome']['description'];
    }
}
