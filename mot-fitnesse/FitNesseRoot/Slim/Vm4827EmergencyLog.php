<?php

use DvsaCommon\Dto\MotTesting\ContingencyTestDto;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm4827EmergencyLog
{
    protected $emergencyCode;
    protected $testDate;
    protected $site;
    protected $reasonCode;
    protected $otherReasonText;
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
     * @param mixed $otherReasonText
     */
    public function setOtherReasonText($otherReasonText)
    {
        $this->otherReasonText = $otherReasonText;
    }

    /**
     * @param mixed $site
     */
    public function setTestSite($site)
    {
        $this->site = $site;
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

        $postArray = [
            'siteId'            => $this->site,
            'contingencyCode'   => $this->emergencyCode,
            'performedAtYear'   => $testDate->format('Y'),
            'performedAtMonth'  => $testDate->format('m'),
            'performedAtDay'    => $testDate->format('d'),
            'performedAtHour'   => $testDate->format('g'),
            'performedAtMinute' => $testDate->format('i'),
            'performedAtAmPm'   => $testDate->format('a'),
            'reasonCode'        => $this->reasonCode,
            '_class'            => ContingencyTestDto::class,
        ];

        if ('empty' == $this->otherReasonText) {
            $postArray['otherReasonText'] = $this->otherReasonText;
        }

        $this->response = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider(
                $this->username,
                $this->password),
            (new UrlBuilder())->emergencyLog(),
            $postArray
        );

        $lastInfo = TestShared::$lastInfo;

        return (200 === $lastInfo['http_code']) ? 'pass' : 'fail';
    }

}
