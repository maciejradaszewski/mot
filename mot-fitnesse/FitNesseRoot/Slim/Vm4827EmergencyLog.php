<?php

use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm4827EmergencyLog
{
    protected $emergencyCode;
    protected $testerId;
    protected $testerCode;
    protected $testDate;
    protected $site;
    protected $testType;
    protected $reasonCode;
    protected $reasonText;
    protected $response;

    protected $username = 'tester1';
    protected $password = TestShared::PASSWORD;

    /**
     * @param mixed $emergencyCode
     */
    public function setEmergencyCode($emergencyCode)
    {
        $this->emergencyCode = $emergencyCode;
    }

    /**
     * @param mixed $reasonCode
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
    }

    /**
     * @param mixed $reasonText
     */
    public function setReasonText($reasonText)
    {
        $this->reasonText = $reasonText;
    }

    /**
     * @param mixed $site
     */
    public function setTestSite($site)
    {
        $this->site = $site;
    }

    /**
     * @param mixed $testType
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;
    }

    /**
     * @param mixed $testerId
     */
    public function setTesterId($testerId)
    {
        $this->testerId = $testerId;
    }

    /**
     * @param mixed $testerCode
     */
    public function setTesterCode($testerCode)
    {
        $this->testerCode = $testerCode;
    }

    /**
     * @param mixed $testDate
     */
    public function setTestDate($testDate)
    {
        $this->testDate = $testDate;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Uses all currently set values to make a request to the server
     *
     * @return string
     */
    public function response()
    {
        // DTO workaround: convert into a D/M/Y string for transport
        // the controller does the converse operation prior to DTO validation
        $testDate = new \DateTime();
        if ('now' != $this->testDate) {
            $testDate->modify($this->testDate);
        }

        $postArray
            = [
            'testerCode'      => $this->testerCode,
            'testedByWhom'    => $this->testerId,
            'siteId'          => $this->site,
            'testType'        => $this->testType,
            'contingencyCode' => $this->emergencyCode,
            'performedAt'     => $testDate->format('Y-m-d'),
            'dateYear'        => $testDate->format('Y'),
            'dateMonth'       => $testDate->format('m'),
            'dateDay'         => $testDate->format('d'),
            'reasonCode'      => $this->reasonCode,
            '_class'          => ContingencyMotTestDto::class,
        ];

        if ('empty' == $this->reasonText) {
            $postArray['reasonText'] =$this->reasonText;
        }

        $this->response = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider(
                $this->username,
                $this->password),
            (new UrlBuilder())->emergencyLog(),
            $postArray
        );

        $lastInfo = TestShared::$lastInfo;

        if ($lastInfo['http_code'] == 200) {
            return "pass";
        }
        return "fail";
    }

}
